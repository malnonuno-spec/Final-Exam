<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Student;
use App\Models\Course;
use App\Models\Section;
use App\Models\Schedule;

class UcasApiController extends Controller
{
    /**
     * Handle login request
     */
    public function loginAndStore(Request $request)
    {
        try {
            $response = Http::asForm()->post('https://quiztoxml.ucas.edu.ps/api/login',
            [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ]);

            $data = $response->json();

            if ($response->successful()) {

                if (
                    isset($data['success']) && $data['success'] === true &&
                    isset($data['Token']) &&
                    isset($data['data'])
                ) {
                    $userData = $data['data'];

                    $user = (is_array($userData) && isset($userData[0]))
                        ? $userData[0]
                        : $userData;

                    if (!isset($user['user_id'])) {
                        return response()->json([
                            'error' => 'Invalid user data format.',
                            'response' => $data
                        ], 500);
                    }

                    $student = Student::updateOrCreate(
                        ['student_id' => $user['user_id']],
                        [
                            'student_name' => $user['user_ar_name'] ?? $user['user_en_name'],
                            'token' => $data['Token'],
                        ]
                    );

                    return response()->json([
                        'message' => 'Login successful and data stored.',
                        'student' => $student
                    ], 200);
                }

                return response()->json([
                    'error' => 'Unexpected data format from API.',
                    'response' => $data
                ], 500);
            }

            return response()->json([
                'error' => $data['message'] ?? 'Login failed'
            ], 401);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Internal server error.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch and store schedule (RELATIONAL)
     */
    public function getAndStoreSchedule(Request $request)
    {
        try {
            $studentId = $request->input('user_id');
            $token = $request->input('token');

            if (!$studentId || !$token) {
                return response()->json([
                    'error' => 'User ID and token are required.'
                ], 400);
            }

            $response = Http::asForm()->post('https://quiztoxml.ucas.edu.ps/api/get-table', [
                'user_id' => $studentId,
                'token' => $token,
            ]);

            $data = $response->json();

            if ($response->successful()) {

                if (!isset($data['data']) || !is_array($data['data'])) {
                    return response()->json([
                        'error' => 'Invalid schedule format.',
                        'response' => $data
                    ], 500);
                }

                // Delete old schedule
                Schedule::where('student_id', $studentId)->delete();

                //  Map days
                $daysMap = [
                    'M' => 'Monday',
                    'T' => 'Tuesday',
                    'W' => 'Wednesday',
                    'R' => 'Thursday',
                    'S' => 'Saturday',
                    'N' => 'Sunday',
                ];

                foreach ($data['data'] as $item) {

                    // Correct keys based on real API
                    $courseCode = $item['subj_no'] ?? md5($item['subject_name']);
                    $courseName = $item['subject_name'] ?? 'Unknown Course';
                    $sectionNumber = $item['branch_no'] ?? 'N/A';

                    // Create course
                    $course = Course::updateOrCreate(
                        ['course_code' => $courseCode],
                        ['course_name' => $courseName]
                    );

                    // Create section
                    $section = Section::updateOrCreate(
                        [
                            'course_id' => $course->id,
                            'section_number' => $sectionNumber
                        ],
                        [
                            'instructor' => $item['teacher_name'] ?? null
                        ]
                    );

                    // Loop through days (REAL FIX)
                    foreach ($daysMap as $key => $dayName) {

                        if (!empty($item[$key])) {

                            $timeParts = explode('-', $item[$key]);

                            $startTime = trim($timeParts[0] ?? 'N/A');
                            $endTime = trim($timeParts[1] ?? 'N/A');

                            Schedule::create([
                                'student_id' => $studentId,
                                'course_id' => $course->id,
                                'section_id' => $section->id,
                                'day' => $dayName,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'room' => $item['room_no'] ?? null,
                            ]);
                        }
                    }
                }

                return response()->json([
                    'message' => 'Schedule stored successfully (relational).'
                ], 200);
            }

            return response()->json([
                'error' => 'Failed to retrieve schedule.',
                'response' => $data
            ], $response->status());

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Internal server error.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

        // View schedule for a student
        public function viewSchedule($studentId)
        {
            $schedule = Schedule::with(['course', 'section'])
                ->where('student_id', $studentId)
                ->orderBy('day')
                ->get();

            $student_name = Student::where('student_id', $studentId)->value('student_name');

            return view('schedule', compact('schedule', 'studentId', 'student_name'));
        }


}

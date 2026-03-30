<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الجدول الدراسي | الكلية الجامعية للعلوم التطبيقية </title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .fade-transition {
            transition: all 0.2s ease;
        }
        .schedule-row {
            transition: transform 0.15s ease, background-color 0.2s;
        }
        .schedule-row:hover {
            transform: scale(1.01);
            background-color: #fef9e3;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 p-6">

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h1 class="text-3xl font-bold text-white text-center flex items-center justify-center gap-3">
                    <i class="fas fa-calendar-alt"></i>
                    جدولي الدراسي - الفصل الأول 2025/2026
                </h1>
            </div>
            <div class="px-6 py-5 flex flex-wrap justify-between items-center gap-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-user-graduate text-indigo-700 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">رقم الجامعي</p>
                        <p class="text-xl font-bold text-gray-800">{{ $studentId }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-user text-green-700 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">اسم الطالب</p>
                        <p class="text-xl font-bold text-gray-800">{{ $student_name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chalkboard-user text-blue-700 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">عدد المساقات</p>
                        <p class="text-xl font-bold text-gray-800">{{ count($schedule) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex flex-wrap justify-between items-center gap-3">
            <div class="relative flex-grow max-w-md">
                <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن اسم المادة أو القسم أو اليوم..."
                    class="w-full pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition">
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <button id="exportBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> تصدير Excel
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="scheduleTable">
                    <thead class="bg-gradient-to-r from-indigo-700 to-blue-700">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">المساق</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">الشعبة</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">اسم المدرس</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">اليوم</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">الوقت</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-white uppercase tracking-wider">القاعة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="scheduleBody">
                        @forelse($schedule as $item)
                        <tr class="schedule-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-book text-indigo-500"></i>
                                    <span class="font-medium text-gray-800">{{ $item->course->course_name ?? $item['course_name'] ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $item->section->section_number ?? $item['section_number'] ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $item->section->instructor ?? $item['instructor'] ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-week text-gray-500"></i>
                                    {{ $item->day ?? $item['day'] ?? '—' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class="far fa-clock text-gray-500"></i>
                                    {{ $item->start_time ?? $item['start_time'] ?? '' }} - {{ $item->end_time ?? $item['end_time'] ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-door-open text-gray-500"></i>
                                    {{ $item->room ?? $item['room'] ?? '—' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-database text-4xl mb-2 block"></i>
                                لا يوجد جدول دراسي لعرضه
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 text-center text-gray-500 text-sm">
            <i class="fas fa-university"></i> الكلية الجامعية للعلوم التطبيقية - نظام إدارة الجداول الدراسية
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#scheduleBody tr');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('exportBtn').addEventListener('click', function() {
            let csv = [];
            const rows = document.querySelectorAll('#scheduleTable tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cols = row.querySelectorAll('td, th');
                const rowData = [];
                cols.forEach(col => {
                    let text = col.innerText.replace(/,/g, ' ') || '';
                    rowData.push(text);
                });
                csv.push(rowData.join(','));
            }
            const blob = new Blob(["\uFEFF" + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'schedule.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });
    </script>
</body>
</html>

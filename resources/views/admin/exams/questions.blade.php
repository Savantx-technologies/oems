@extends('layouts.admin')

@section('title','Select Questions')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Select Questions
            </h1>
            <p class="text-sm text-gray-500">
                {{ $exam->title }} – Class {{ $exam->class }} | {{ $exam->subject }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.exams.index') }}"
                class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back to exams
            </a>

            <a href="{{ route('admin.questions.create') }}" target="_blank"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                + Add Question
            </a>
        </div>
    </div>

    <form id="attachForm" method="POST" action="{{ route('admin.exams.attach',$exam->id) }}">
        @csrf

        <!-- Top summary & filters -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">

            <div class="grid md:grid-cols-6 gap-4 items-end">

                <div>
                    <label class="text-xs text-gray-500">Difficulty</label>
                    <select id="filterDifficulty" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">All</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

            </div>

        </div>

        <!-- Question table -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-5 py-3 w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600">
                            </th>
                            <th class="px-5 py-3 text-left">Class</th>
                            <th class="px-5 py-3 text-left">Subject</th>
                            <th class="px-5 py-3 text-left">Question</th>
                            <th class="px-5 py-3 text-left">Difficulty</th>
                            <th class="px-5 py-3 text-left w-24">Marks</th>
                        </tr>
                    </thead>

                    <tbody id="questionTable" class="divide-y">

                        @foreach($questions as $q)
                        <tr class="question-row hover:bg-gray-50" data-grade="{{ strtolower($q->class) }}"
                            data-subject="{{ strtolower($q->subject) }}"
                            data-difficulty="{{ strtolower($q->difficulty) }}">

                            <td class="px-5 py-3">
                                <input type="checkbox" class="question-check rounded border-gray-300 text-indigo-600"
                                    name="questions[]" value="{{ $q->id }}" data-marks="{{ $q->marks }}" {{
                                    in_array($q->id, $attached) ? 'checked' : '' }}>

                            </td>

                            <td class="px-5 py-3">
                                {{ $q->class }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $q->subject }}
                            </td>

                            <td class="px-5 py-3 text-gray-800 question-text">
                                {{ $q->question_text }}
                            </td>
                            <td class="px-5 py-3 text-gray-800 question-text">
                                {{ $q->difficulty }}
                            </td>

                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ $q->marks }}
                                </span>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="px-6 py-4 border-t bg-white flex justify-end">
                <button type="button" id="previewBtn"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Attach Selected Questions
                </button>

            </div>

        </div>

    </form>
    <!-- Confirm Modal -->
    <div id="confirmBox" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden
            flex items-center justify-center z-50 p-4">

        <div class="bg-white rounded-2xl shadow-2xl
                w-full max-w-4xl overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b bg-gradient-to-r from-indigo-50 to-white">

                <h3 class="text-lg font-semibold text-gray-800">
                    Confirm Questions for Exam
                </h3>

                <p class="text-sm text-gray-600 mt-1">
                    {{ $exam->title }}
                    <span class="mx-2">•</span>
                    Class {{ $exam->class }}
                    <span class="mx-2">•</span>
                    {{ $exam->subject }}
                </p>

            </div>

            <!-- Table Section -->
            <div class="max-h-[400px] overflow-y-auto px-6 py-4 bg-gray-50">

                <table class="min-w-full text-sm border-separate border-spacing-y-2">

                    <thead>
                        <tr class="text-xs uppercase text-gray-500">
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Difficulty</th>
                            <th class="px-4 py-2 text-left">Question</th>
                            <th class="px-4 py-2 text-right">Marks</th>
                        </tr>
                    </thead>

                    <tbody id="confirmTable">
                        <!-- JS will insert rows here -->
                    </tbody>

                </table>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t bg-white flex justify-end gap-3">

                <button type="button" id="cancelConfirm" class="px-5 py-2 rounded-lg border border-gray-300
                       text-sm text-gray-600 hover:bg-gray-100 transition">
                    Cancel
                </button>

                <button type="submit" form="attachForm" class="px-6 py-2 rounded-lg bg-indigo-600 text-white
                       text-sm font-medium hover:bg-indigo-700 shadow transition">
                    Confirm & Attach
                </button>

            </div>

        </div>

    </div>



</div>


<script>
    const checks = document.querySelectorAll('.question-check');
const rows   = document.querySelectorAll('.question-row');

const filterDifficulty = document.getElementById('filterDifficulty');

const previewBtn   = document.getElementById('previewBtn');
const confirmBox   = document.getElementById('confirmBox');
const confirmTable = document.getElementById('confirmTable');
const cancelBtn    = document.getElementById('cancelConfirm');

const selectedCount = document.getElementById('selectedCount'); // may not exist
const totalMarks    = document.getElementById('totalMarks');    // may not exist


/* -----------------------------
   Summary (safe – only if exists)
--------------------------------*/
function updateSummary(){

    let c = 0;
    let m = 0;

    checks.forEach(ch=>{
        if(ch.checked){
            c++;
            m += parseInt(ch.dataset.marks || 0);
        }
    });

    if(selectedCount) selectedCount.innerText = c;
    if(totalMarks) totalMarks.innerText = m;
}

checks.forEach(ch=>{
    ch.addEventListener('change', updateSummary);
});


/* -----------------------------
   Difficulty filter only
--------------------------------*/
function applyFilter(){

    const d = filterDifficulty.value.toLowerCase().trim();

    rows.forEach(r=>{

        const rd = (r.dataset.difficulty || '').toLowerCase();

        let show = true;

        if(d && rd !== d) show = false;

        r.style.display = show ? '' : 'none';
    });
}

filterDifficulty.addEventListener('change', applyFilter);


/* -----------------------------
   Preview before attach
--------------------------------*/
previewBtn.addEventListener('click', function(){

    confirmTable.innerHTML = '';

    let index = 1;

    document.querySelectorAll('.question-check:checked').forEach(ch => {

        const row = ch.closest('tr');

        const qtext = row.querySelector('.question-text').innerText;
        const marks = ch.dataset.marks;
        const diff  = row.dataset.difficulty;

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="px-3 py-2">${index++}</td>
            <td class="px-3 py-2 capitalize">${diff}</td>
            <td class="px-3 py-2">${qtext}</td>
            <td class="px-3 py-2">${marks}</td>
        `;

        confirmTable.appendChild(tr);
    });

    if(index === 1){
        alert('Please select at least one question');
        return;
    }

    confirmBox.classList.remove('hidden');
    confirmBox.classList.add('flex');
});


/* -----------------------------
   Close preview
--------------------------------*/
cancelBtn.addEventListener('click', function(){
    confirmBox.classList.add('hidden');
    confirmBox.classList.remove('flex');
});


/* -----------------------------
   Initial
--------------------------------*/
updateSummary();
applyFilter();


document.getElementById('selectAll').addEventListener('change', function () {

    const isChecked = this.checked;

    const rows = document.querySelectorAll('.question-row');

    rows.forEach(row => {

        const checkbox = row.querySelector('.question-check');

        if (checkbox) {
            checkbox.checked = isChecked;
        }

    });

});

</script>


@endsection
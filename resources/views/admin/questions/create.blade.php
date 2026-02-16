@extends('layouts.admin')

@section('title','Add Question')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Add Question</h1>
            <p class="text-sm text-gray-500">Create and manage your question bank</p>
        </div>

        <div class="text-sm text-gray-600">
            Total questions :
            <span class="font-semibold text-gray-900">{{ $total }}</span>
        </div>
    </div>

    {{-- Recently added (only current session) --}}
    <div id="recentBox" class="bg-white border border-gray-200 rounded-xl shadow-sm hidden">


        <div class="px-5 py-3 border-b flex justify-between">
            <span class="text-sm font-semibold text-gray-800">
                Questions added in this session
            </span>

            <span id="recentCount" class="text-xs text-gray-500">
                {{ $questions->count() }} added
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-4 py-2 w-12">#</th>
                        <th class="px-4 py-2">Class</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Question</th>
                        <th class="px-4 py-2">Marks</th>
                    </tr>
                </thead>

                <tbody id="recentTable" class="divide-y">
                    @foreach($questions as $index => $q)
                    <tr>
                        <td class="px-4 py-2 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $q->class }}</td>
                        <td class="px-4 py-2">{{ $q->subject }}</td>
                        <td class="px-4 py-2 capitalize">{{ $q->type }}</td>
                        <td class="px-4 py-2 text-gray-700">
                            {{ Str::limit($q->question_text,70) }}
                        </td>
                        <td class="px-4 py-2">{{ $q->marks }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>


    {{-- Add Question Form --}}
    <form id="questionForm" method="POST" action="{{ route('admin.questions.store') }}">
        @csrf

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">New Question</h2>
                <p class="text-sm text-gray-500">Enter question details</p>
            </div>

            <div class="p-6 space-y-6">

                <!-- Basic -->
                <div class="grid md:grid-cols-3 gap-5">

                    <div>
                        <label class="block text-sm font-medium mb-1">Class / Grade</label>
                        <input type="text" name="class" id="classInput" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Subject</label>
                        <input type="text" name="subject" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Question type</label>
                        <select name="type" id="questionType" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="mcq">MCQ</option>
                            {{-- <option value="subjective">Subjective</option> --}}
                            <option value="summary">Summary (Passage)</option>
                        </select>
                    </div>

                </div>

                {{-- Passage block --}}
                <div id="summaryBlock" class="space-y-2">

                    <label class="block text-sm font-medium">
                        Passage (for summary / comprehension)
                    </label>

                    <div class="flex gap-2">

                        <select name="passage_id" id="passageSelect"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                            <option value="">-- Select passage --</option>

                            @foreach($passages as $p)
                            <option value="{{ $p->id }}" data-content="{{ e($p->content) }}">
                                {{ $p->title ?? 'Passage #'.$p->id }}
                            </option>
                            @endforeach

                        </select>

                        <a href="{{ route('admin.passages.create') }}" target="_blank" class="px-4 py-2 rounded-lg border border-indigo-600
                                  text-indigo-600 text-sm hover:bg-indigo-50">
                            + Add Passage
                        </a>

                    </div>

                    <div id="passagePreviewBox"
                        class="hidden p-4 border rounded-lg bg-gray-50 text-sm text-gray-700 whitespace-pre-line">
                    </div>

                </div>

                <!-- Question -->
                <div>
                    <label class="block text-sm font-medium mb-1">Question</label>
                    <textarea name="question_text" rows="3" required
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium mb-1">Marks</label>
                        <input type="number" name="marks" min="1" value="1" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <select name="difficulty" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select difficulty</option>
                            @foreach($difficulties as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                            @endforeach

                        </select>
                    </div>

                </div>

            </div>

            {{-- MCQ block --}}
            <div id="mcqBlock" class="border-t px-6 py-5">

                <h3 class="text-sm font-semibold mb-4">
                    Answer options (MCQ only)
                </h3>

                <div class="space-y-3">

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="A" class="text-indigo-600 border-gray-300">
                        <input type="text" name="option_a" id="optA"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Option A">
                        <span class="text-xs text-gray-400">A</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="B" class="text-indigo-600 border-gray-300">
                        <input type="text" name="option_b" id="optB"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Option B">
                        <span class="text-xs text-gray-400">B</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="C" class="text-indigo-600 border-gray-300">
                        <input type="text" name="option_c" id="optC"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Option C">
                        <span class="text-xs text-gray-400">C</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="D" class="text-indigo-600 border-gray-300">
                        <input type="text" name="option_d" id="optD"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Option D">
                        <span class="text-xs text-gray-400">D</span>
                    </div>

                </div>

            </div>


            <!-- Actions -->
            <div class="px-6 py-4 border-t flex justify-between">

                <p class="text-xs text-gray-500">
                    This question will be added to question bank
                </p>

                <div class="flex gap-3">

                    <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded-lg border text-sm">
                        Cancel
                    </a>

                    <button type="submit" name="save_add_more" value="1" id="saveAddMoreBtn"
                        class="px-4 py-2 rounded-lg bg-indigo-100 text-indigo-700 text-sm">
                        Save & Add more
                    </button>

                    <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm">
                        Save & Close
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


<script>
const typeSelect    = document.getElementById('questionType');
const mcqBlock      = document.getElementById('mcqBlock');
const summaryBlock  = document.getElementById('summaryBlock');
const passageSelect = document.getElementById('passageSelect');
const previewBox    = document.getElementById('passagePreviewBox');
const form          = document.getElementById('questionForm');
const saveAddBtn    = document.getElementById('saveAddMoreBtn');

const recentBox   = document.getElementById('recentBox');
const recentCount = document.getElementById('recentCount');
const recentTable = document.getElementById('recentTable');

const classInput  = document.getElementById('classInput');


/* -------------------------------------------------
   Focus option textbox when radio is selected
--------------------------------------------------*/
document.querySelectorAll('input[name="correct_option"]').forEach(radio => {

    radio.addEventListener('change', function () {

        if (this.value === 'A') document.getElementById('optA').focus();
        if (this.value === 'B') document.getElementById('optB').focus();
        if (this.value === 'C') document.getElementById('optC').focus();
        if (this.value === 'D') document.getElementById('optD').focus();

    });

});


/* -------------------------------------------------
   Toggle MCQ / Summary blocks
--------------------------------------------------*/
function toggleBlocks(){

    const type = typeSelect.value;

    mcqBlock.style.display = (type === 'mcq') ? 'block' : 'none';
    mcqBlock.querySelectorAll('input').forEach(i=>{
        i.disabled = type !== 'mcq';
    });

    summaryBlock.style.display = (type === 'summary') ? 'block' : 'none';
    summaryBlock.querySelectorAll('select').forEach(s=>{
        s.disabled = type !== 'summary';
    });

    if(type !== 'summary'){
        previewBox.classList.add('hidden');
    }
}

typeSelect.addEventListener('change', toggleBlocks);


/* -------------------------------------------------
   Passage preview
--------------------------------------------------*/
function updatePreview(){

    const option = passageSelect.options[passageSelect.selectedIndex];

    if(!option || !option.dataset.content){
        previewBox.innerHTML = '';
        previewBox.classList.add('hidden');
        return;
    }

    previewBox.textContent = option.dataset.content;
    previewBox.classList.remove('hidden');
}

passageSelect.addEventListener('change', updatePreview);


/* -------------------------------------------------
   Replace correct option (A/B/C/D → actual value)
--------------------------------------------------*/
function replaceCorrectWithValue(){

    const checked = document.querySelector('input[name="correct_option"]:checked');

    if(!checked){
        alert('Please select correct option');
        return false;
    }

    let value = '';

    if(checked.value === 'A') value = document.getElementById('optA').value.trim();
    if(checked.value === 'B') value = document.getElementById('optB').value.trim();
    if(checked.value === 'C') value = document.getElementById('optC').value.trim();
    if(checked.value === 'D') value = document.getElementById('optD').value.trim();

    if(value === ''){
        alert('Selected correct option text is empty.');
        return false;
    }

    // store real value (example: 4)
    checked.value = value;

    return true;
}


/* -------------------------------------------------
   Save & Add More (AJAX)
--------------------------------------------------*/
saveAddBtn.addEventListener('click', function(e){

    e.preventDefault();

    if(typeSelect.value === 'mcq'){
        if(!replaceCorrectWithValue()) return;
    }

    const formData = new FormData(form);
    formData.append('save_add_more', 1);

    fetch(form.action,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'X-Requested-With':'XMLHttpRequest',
            'Accept':'application/json'
        },
        body:formData
    })
    .then(async response => {

        if(!response.ok){

            const err = await response.json();

            if(err.errors){
                let msg = Object.values(err.errors).flat().join("\n");
                alert(msg);
            }else{
                alert('Server error');
            }

            throw new Error('validation');
        }

        return response.json();
    })
    .then(data => {

        if(recentBox){
            recentBox.classList.remove('hidden');
        }

        const index = recentTable
            ? recentTable.querySelectorAll('tr').length + 1
            : 1;

        if(recentTable){

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td class="px-4 py-2 text-gray-500">${index}</td>
                <td class="px-4 py-2">${data.class}</td>
                <td class="px-4 py-2">${data.subject}</td>
                <td class="px-4 py-2 capitalize">${data.type}</td>
                <td class="px-4 py-2 text-gray-700">
                    ${data.question.substring(0,70)}
                </td>
                <td class="px-4 py-2">${data.marks}</td>
            `;

            recentTable.prepend(tr);
        }

        if(recentCount && recentTable){
            const total = recentTable.querySelectorAll('tr').length;
            recentCount.innerText = total + ' added';
        }

        // reset only question + options
        form.querySelector('textarea[name="question_text"]').value = '';

        if(typeSelect.value === 'mcq'){

            document.getElementById('optA').value = '';
            document.getElementById('optB').value = '';
            document.getElementById('optC').value = '';
            document.getElementById('optD').value = '';

            document.querySelectorAll('input[name="correct_option"]').forEach(r=>{
                r.checked = false;
                r.value   = r.defaultValue; // restore A/B/C/D
            });
        }

        updatePreview();

    })
    .catch(err => console.log(err));

});


/* -------------------------------------------------
   Normal submit (Save & Close)
--------------------------------------------------*/
form.addEventListener('submit', function (e) {

    if(typeSelect.value === 'mcq'){
        if(!replaceCorrectWithValue()){
            e.preventDefault();
        }
    }

});


/* -------------------------------------------------
   Filter recent by class
--------------------------------------------------*/
function filterRecentByClass(){

    if(!recentTable || !recentBox) return;

    const cls = classInput.value.trim().toLowerCase();

    if(cls === ''){
        recentBox.classList.add('hidden');
        return;
    }

    let visibleCount = 0;

    recentTable.querySelectorAll('tr').forEach(row => {

        const classCell = row.children[1];
        if(!classCell) return;

        const rowClass = classCell.textContent.trim().toLowerCase();

        if(rowClass === cls){
            row.style.display = '';
            visibleCount++;
        }else{
            row.style.display = 'none';
        }

    });

    if(visibleCount > 0){
        recentBox.classList.remove('hidden');
        if(recentCount) recentCount.innerText = visibleCount + ' added';
    }else{
        recentBox.classList.add('hidden');
    }
}

if(classInput){
    classInput.addEventListener('input', filterRecentByClass);
}


/* -------------------------------------------------
   Initial
--------------------------------------------------*/
toggleBlocks();
updatePreview();
</script>





@endsection
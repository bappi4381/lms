{{-- Alpine quiz-builder factory. $initialQuestions: array (empty for create, hydrated for edit) --}}
<script>
function quizBuilder() {
    return {
        questions: @json($initialQuestions ?? []),
        init() {
            if (this.questions.length === 0) {
                this.addQuestion();
            }
        },
        addQuestion() {
            this.questions.push({
                question: '',
                type: 'single',
                points: 1,
                choices: [
                    { choice_text: '', is_correct: false },
                    { choice_text: '', is_correct: false }
                ]
            });
        },
        removeQuestion(qIdx) {
            this.questions.splice(qIdx, 1);
        },
        addChoice(qIdx) {
            this.questions[qIdx].choices.push({ choice_text: '', is_correct: false });
        },
        removeChoice(qIdx, cIdx) {
            this.questions[qIdx].choices.splice(cIdx, 1);
        },
        syncJSON() {
            document.getElementById('questions_json').value = JSON.stringify(this.questions);
        }
    }
}
</script>

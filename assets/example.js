$(document).ready(function () {
    $('.submit-answer').on('click', function () {
        const button = $(this);
        const answerInput = button.siblings('.answer-input');
        const equation = button.data('equation');
        const difficulty = button.data('difficulty');
        const theme = button.data('theme');

        $.ajax({
            type: 'POST',
            url: '/solve',
            data: {
                equation: equation,
                difficulty: difficulty,
                theme: theme
            },
            success: function (response) {
                const answerDiv = button.closest('.example').find('.answer');
                const results = button.closest('.exercise').find('.results');
                answerDiv.text(response.solution);
                const responseSolution = parseFloat(response.solution);
                const userAnswer = parseFloat(answerInput.val().trim());

                if (responseSolution === userAnswer) {
                    answerDiv.removeClass('incorrect_result');
                    answerDiv.addClass('correct_result');
                    answerDiv.text(response.solution);
                    updateLocalStorage(true);
                    results.text(parseInt(localStorage.getItem('correctCount')) + " / " + parseInt(localStorage.getItem('incorrectCount')));
                } else {
                    answerDiv.removeClass('correct_result');
                    answerDiv.addClass('incorrect_result');
                    answerDiv.text(response.solution);
                    updateLocalStorage(false);
                    results.text(parseInt(localStorage.getItem('correctCount')) + " / " + parseInt(localStorage.getItem('incorrectCount')));
                }
                answerDiv.show();
                results.show();

                const stepsDiv = button.closest('.example').find('.steps');
                stepsDiv.empty();
                response.steps.forEach(function (step) {
                    const stepDiv = $('<div class="step"></div>').text(step);
                    stepsDiv.append(stepDiv);
                });

                stepsDiv.show();
            },
            error: function (xhr) {
                const answerDiv = button.closest('.example').find('.answer');
                answerDiv.text(`Chyba: ${xhr.responseJSON.error}`).show();
            }
        });
    });
});

function updateLocalStorage(isCorrect) {
    let correctCount = parseInt(localStorage.getItem('correctCount')) || 0;
    let incorrectCount = parseInt(localStorage.getItem('incorrectCount')) || 0;

    if (isCorrect) {
        correctCount++;
    } else {
        incorrectCount++;
    }

    localStorage.setItem('correctCount', correctCount);
    localStorage.setItem('incorrectCount', incorrectCount);
}


window.addEventListener('beforeunload', function () {
    localStorage.removeItem('correctCount');
    localStorage.removeItem('incorrectCount');
});

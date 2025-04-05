import './styles/Admin/Entity/_entity.scss';
import './styles/Admin/_forms.scss';
import './styles/Admin/_admin.scss';
$(document).ready(function() {
    $('.openDialog').on('click', function(event) {
        event.preventDefault();

        var url = $(this).attr('href');
        window.open(url, 'dialogWindow', 'width=800,height=500,scrollbars=yes,resizable=yes');
    });

    $('.cancelButton').on('click', function(event) {
        event.preventDefault();

        window.close();
    });
});

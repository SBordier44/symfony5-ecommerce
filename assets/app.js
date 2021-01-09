import './styles/app.scss';
import 'jquery';
import 'popper.js';
import './bootstrap';

$("input[type=file]").change(function (e) {
    $(this).next('.custom-file-label').text(e.target.files[0].name);
})

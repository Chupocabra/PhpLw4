$('#auth-form').submit(async function (e) {
    e.preventDefault();
    let email = $('#email').val(),
        pwd = $('#pwd').val();
    if(checkEmail()){
        $.ajax({
            url: '/authorization/login',
            type: 'POST',
            dataType: 'json',
            data: {
                'email': email,
                'pwd': pwd
            },
            success (data) {
                if(data.status) {
                    alert(data['message']);
                    window.location.href='/';
                }
                else {
                    alert(data['message']);
                }
            }
        });
    }
});
function checkEmail(){
    var email_reg = /^[0-9a-zA-Z][0-9a-zA-Z_]{3,29}\@[A-Za-z]{2,30}\.[a-z]{2,10}/;
    let validation = true;
    $('#validationError').text('');
    $('#email').css("border-color", "#4a90e2");
    if(!email_reg.test(email.value)){
        validation = false;
        $('#validationError').append("<br>Формат почты (test@mail.ru)");
        $('#email').css("border-color", "#F77A52");
    }
    return validation;
}

$('#exit').click(function () {
    $.ajax({
        url: '/logout',
        processData: false,
        dataType: 'json',
        contentType: false,
        cache: false,
        success(data) {
            alert(data['message']);
            window.location.href = '/';
        }
    })
});

$('#reg-form').submit(function (e){
    e.preventDefault();
    if(validate()){
        console.log("валидация прошла успешно");
        let name = $('#name').val(),
            email = $('#email').val(),
            pwd = $('#pwd').val();
        $.ajax({
            url: '/registration/send',
            type: 'POST',
            dataType: 'json',
            data: {
                'name': name,
                'email': email,
                'pwd': pwd
            },
            success (data) {
                if(data.status) {
                    alert(data['message']);
                    window.location.href='/';
                }
                else {
                    alert(data['message']);
                }
            }
        })
    }
})

function validate(){
    $('#validationError').text('');
    $('#email').css("border-color", "#4a90e2");
    $('#name').css("border-color", "#4a90e2");
    var fio_reg = /^[A-z]{2,30}/;
    var email_reg = /^[0-9a-zA-Z][0-9a-zA-Z_]{3,29}\@[A-Za-z]{2,30}\.[a-z]{2,10}/;
    var validation = true;
    if(!fio_reg.test($('#name').val())){
        validation = false;
        $('#validationError').append("<br>Введите никнейм агнглийскими буквами");
        $('#name').css("border-color", "#F77A52");
    }
    if(!email_reg.test($('#email').val())){
        validation = false;
        $('#validationError').append("<br>Формат почты (test@mail.ru)");
        $('#email').css("border-color", "#F77A52");
    }
    return validation;
}

var bookCover=null, bookFile=null;

$('#bookCover').change(function (e){
    bookCover = e.target.files[0];
})

$('#bookFile').change(function (e){
    bookFile = e.target.files[0];
})

$('#createForm').submit(function (e){
    e.preventDefault();
    var flag = true;

    let name=$('#bookName').val(),
        author=$('#bookAuthor').val(),
        date=$('#bookReaded').val();
    if(date===''){
        flag=false;
        $('#bookAddError').append("<br>Введите дату прочтения книги");
    }
    let formData = new FormData();
    formData.append('name', name);
    formData.append('author', author);
    formData.append('date', date);
    if (bookCover != null){
        formData.append('cover', bookCover);
    }
    if (bookFile != null) {
        formData.append('file', bookFile);
    }
    if (flag) {
        $.ajax({
            url: '/create/book',
            type: 'POST',
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success(data){
                alert(data['message']);
                window.location.href='/';
            }
        })
    }
})
$('#updateCover').change(function (e) {
    updateCover = e.target.files[0];
})

$('#updateFile').change(function (e) {
    updateFile = e.target.files[0];
})

$('#deleteCover').click( function () {
    if (updateCover === null) {
        updateCover = true;
    }
});

$('#deleteFile').click( function () {
    if (updateFile === null) {
        updateFile = true;
    }
});
$('#updateForm').submit(function (e){
    e.preventDefault();
    $('#bookUpdateError').text('');
    let name = $('#updateName').val(),
        author = $('#updateAuthor').val(),
        readed = $('#updateReaded').val(),
        id = $('#bookId').val();

    let flag = true;
    if (name === '') {
        flag = false;
        $('#bookUpdateError').append("<br>Введите название книги");
    }
    if (author === '') {
        flag = false;
        $('#bookUpdateError').append("<br>Введите имя автора");
    }
    if (readed === '') {
        flag = false;
        $('#bookUpdateError').append("<br>Введите дату прочтения");
    }

    if (flag) {
        let formData = new FormData();
        formData.append('name', name);
        formData.append('author', author);
        formData.append('readed', readed);

        if (updateCover != null) {
            formData.append('cover', updateCover);
        }

        if (updateFile != null) {
            formData.append('file', updateFile);
        }

        $.ajax({
            url: '/update/book/' + id,
            type: 'POST',
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            data: formData,
            success(data) {
                alert(data['message']);
                window.location.href = '/';
            }
        });
    }
})

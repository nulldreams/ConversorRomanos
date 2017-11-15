$(function()
{
    var listTable = $('#romToDec').DataTable({
        displayLength: 100,
        processing: true,
        serverSide: true,
        order: [[1, "asc"]],
        lengthChange: false,
        info: false,
        ajax: {
            "url": "ajax/getList.php",
            "type": "POST"
        },
        language: {
            search: 'Busca:',
            emptyTable: 'Não​ ​há​ ​itens​ ​a  serem​ ​exibidos.',
            paginate: {
                previous: '<',
                next: '>'
            }
        }
    });

    /*
    * -1 = nenhum
    * 0  = romano para decimal
    * 1  = decimal para romano
    */
    var method = -1;
    var validChars = ['i', 'v', 'x', 'l', 'c', 'd' , 'm'];
    var busy = false;

    var numberDOM = $('#number');

    numberDOM.keypress(function(event)
    {
        if (event.keyCode === 13) // Uma excessão para a tecla enter, permitindo que ela seja usada para dar submit.
            return;

        var isNumeric = isNumberKC(event.keyCode);

        var key = event.key.toLowerCase();

        if (isNumeric)
        {
            if (method !== 1)
            {
                $(this).val('');
                method = 1;
            }

            return;
        }

        if (validChars.includes(key))
        {
            if (method !== 0)
            {
                $(this).val('');
                method = 0;
            }

            return;
        }

        event.preventDefault();
    });

    numberDOM.keyup(function(event)
    {
        var value = $(this).val();

        if (value.length === 0)
        {
            method = -1;

            return;
        }

        if (method === 1 && parseInt() > 3999)
            $(this).val(3999);
    });

    $('#conversor').submit(function(event)
    {
        event.preventDefault();

        if (busy)
            return;

        $('#method').val(method);

        var postData = $(this).serializeArray();

        $.ajax({
            url: 'ajax/convert.php',
            method: 'POST',
            data: postData,
            beforeSend: function()
            {
                busy = true;
                $('#number').prop('disabled', true);
            },
            complete: function()
            {
                busy = false;
                $('#number').prop('disabled', false);
            },
            success: function (data)
            {
                console.log(data);
                var dataObj;

                try {
                    dataObj = JSON.parse(data);
                } catch (e){
                    console.log(e);
                }

                if (dataObj)
                {
                    if (dataObj.errors)
                    {
                        displayErrors(dataObj.errors);
                        return;
                    }

                    var resultDOM = $('#result');
                    resultDOM.val(dataObj.convertedValue);
                    resultDOM.addClass('blink');
                    setTimeout(function()  {
                        resultDOM.removeClass('blink');
                    }, 3200);

                    var notifyDOM = $('#notify');

                    if (typeof dataObj.dbData !== 'undefined') {
                        notifyDOM.text('Esse conversão foi registrada em nosso DB.');
                    }
                    else
                    {
                        notifyDOM.text('Um novo registro foi adicionado ao nosso DB.');

                        listTable.ajax.reload();
                    }

                    popup(notifyDOM);
                }
            }
        });
    });
});

function popup(elem)
{
    elem.removeClass('d-none');
    elem.fadeIn(100);

    setTimeout(function() {
        elem.fadeOut();
    }, 5000, 'swing', function()
    {
        elem.addClass('d-none')
    });
}

function isNumberKC(keyCode) {
    return (keyCode >= 48 && keyCode <= 57)
}

function displayErrors(errors)
{
    var errorDOM = $('#error');

    if (errors & (1 << 0)) {
        errorDOM.text('O conjunto contém algarismos inválidos.');
    }

    if (errors & (1 << 1)) {
        errorDOM.text('Apenas os algarismos \'I\', \'X\' e \'C\' podem ser utilizados para somar ou subtrair.');
    }

    if (errors & (1 << 2)) {
        errorDOM.text('Só é permitido um numeral menor para subtrair um numeral maior.');
    }

    if (errors & (1 << 3)) {
        errorDOM.text('Apenas os algarismos \'I\', \'X\', \'C\' e \'M\' podem ser repetidos consecutivamente.');
    }

    if (errors & (1 << 4)) {
        errorDOM.text('Um algarismo pode ser repetido até três vezes consecutivas.');
    }

    if (errors & (1 << 5)) {
        errorDOM.text('O sistema aceita penas números entre 1 e 3999.');
    }

    popup(errorDOM);
}
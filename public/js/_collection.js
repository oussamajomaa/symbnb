

    let index = $('#ad_images div.form-group').length;

    $('#add-image').click(function () {
        // je récupère le prototype des entrées et je remplace le _name_ par le numéro récupéré
        const tmpl = $('#ad_images').data('prototype').replace(/_name_/g, index);
        //j'injecte le code dans la div _ad_images
        $('#ad_images').append(tmpl);
        index++

        handleDeleteButton()
    })

    function handleDeleteButton() {
        $('.del-sous-form').click(function () {
            const target = this.dataset.target
            $(target).remove();
        })
    }
    handleDeleteButton()


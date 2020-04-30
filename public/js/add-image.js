$('#add-image').click(function () {
    const index = +$('#widgets-counter').val();
    console.log(index);
    const template = $('#ad_images').data('prototype').replace(/__name__/g, index);
    console.log(template);
    $('#ad_images').append(template);
    $('#widgets-counter').val(index + 1)
    handleDeleteButtons()
})

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        console.log(target)
        $(target).remove();
    })
}

function updateCounter(){
    const count = +$('#add-image div.form-group').length;
    $('#widgets-counter').val(count);
}
updateCounter()
handleDeleteButtons();
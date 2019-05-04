/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('admin-lte/bootstrap/js/bootstrap')
require('admin-lte/plugins/select2/select2')
require('./inputmask')
require('admin-lte/dist/js/app')
require('cropit')


window.$(document).ready(function () {
    
    // Cropit
    if ($('#image-cropper').length > 0) {
        $('#image-cropper').cropit({
            imageBackground: true
        });
        $('#image-cropper').cropit('imageSrc', $('.cropit-target').val())
        $('.cropit-trigger').on('click', function (e) {
            $('.cropit-target').val($('#image-cropper').cropit('export'))
        })
    }

    

    // Currency input mask
    $('.currency-mask').inputmask("numeric", {
        groupSeparator: ",",
        // digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true
    })
    
    // Slug
    $('.slugify').on('keyup', function () {
        var a = 'àáäâèéëêìíïîòóöôùúüûñçßÿœæŕśńṕẃǵǹḿǘẍźḧ·/_,:;'
        var b = 'aaaaeeeeiiiioooouuuuncsyoarsnpwgnmuxzh------'
        var p = new RegExp(a.split('').join('|'), 'g')

        var val =  $(this).val().toString().toLowerCase()
                    // .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(p, c => b.charAt(a.indexOf(c)))     // Replace special chars
                    .replace(/&/g, '-and-')         // Replace & with 'and'
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '')     
        $(this).val(val)
    })
})
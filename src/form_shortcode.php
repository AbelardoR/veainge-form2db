<?php

function build_form() {
    $form = '';
    $form .= '<form id="miFormulario" method="post" class="plug-form">';
    $form .= '<input type="text" name="name" placeholder="Enter your name" />';
    $form .= '<input type="email" name="email" placeholder="Enter your e-mail" />';
    $form .= '<textarea name="message" id="message" cols="30" rows="10" placeholder="Write your message here"></textarea>';
    $form .= '<input type="submit" id="submit" value="Send" />';
    $form .= '</form>';

    return $form;
}


// Agregamos el shortcode para mostrar un simple formulario
function show_form_shortcode()
{
    return build_form();
}

add_shortcode('display_form', 'show_form_shortcode');
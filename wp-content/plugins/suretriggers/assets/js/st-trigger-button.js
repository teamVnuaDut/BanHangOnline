function st_get_cookie(cookieName) { 
    const regex = new RegExp(cookieName + '=([^;]+)'); 
    const cookieValue = document.cookie.match(regex); 
    return cookieValue ? cookieValue[1] : null; 
}

function st_trigger_ajax(element) {
    var button = element;
    button.disabled = true;
    var form = button.closest("form");
    var formData = new FormData(form);

    var inputTriggerId = button.parentNode.querySelector('input[name="st_trigger_id"]');
    var inputLoadingLabel = button.parentNode.querySelector('input[name="st_loading_label"]');
    var inputClickedLabel = button.parentNode.querySelector('input[name="st_clicked_label"]');
    var inputButtonLabel = button.parentNode.querySelector('input[name="st_button_label"]');
    var inputUserId = button.parentNode.querySelector('input[name="st_user_id"]');

    var cookiename = 'st_trigger_button_clicked_' + inputTriggerId.value;
    var cookie = 'yes_' + inputUserId.value;
    var cookieValue = st_get_cookie(cookiename);

    if (cookieValue === null || cookieValue !== cookie) {
        button.classList.add('st_trigger_button_loading');
        if (inputLoadingLabel.value !== '') {
        button.textContent = inputLoadingLabel.value;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('POST', st_ajax_object.ajax_url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    button.classList.remove('st_trigger_button_loading');
                    button.disabled = false;
                    if (inputClickedLabel.value !== '') {
                        button.textContent = inputClickedLabel.value;
                    } else {
                        button.textContent = inputButtonLabel.value;
                    }
                    if( xhr.responseText != '' ){
                        var response = JSON.parse(xhr.responseText);
                        if (response.data) {
                            location.href = response.data;
                        }
                    }
                }
            }
        };
        xhr.send(formData);
    } else {
        if (inputClickedLabel.value !== '') {
            button.textContent = inputClickedLabel.value;
        }
    }
}
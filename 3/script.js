let form = document.querySelector("form");
form.addEventListener("submit", function (e) {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'form.php');
    let data = new FormData(form);
    xhr.send(data);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert(xhr.statusText);
            if (xhr.status === 200) {
                form.reset();
            }
        }
    };
});
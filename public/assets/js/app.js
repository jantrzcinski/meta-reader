const titleMaxWidth = document.getElementById('titleMaxWidth').innerHTML;
const descriptionMaxLength = document.getElementById('descriptionMaxLength').innerHTML;
const descriptionMaxWidth = document.getElementById('descriptionMaxWidth').innerHTML;


document.getElementById('metaAction').addEventListener("click", metaAction);
document.getElementById('metaForm').addEventListener("submit", metaAction);


document.getElementById('resetForm').addEventListener("click", resetForm);

function addAlert(message) {
    const alert = `<div class="alert alert-danger">${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    document.getElementById('alerts').innerHTML = document.getElementById('alerts').innerHTML + alert;
}
function resetForm(e) {
    e.preventDefault();
    document.getElementById("metaForm").reset();

    const titleLength = document.getElementById('titleLength');
    const titleWidth = document.getElementById('titleWidth');

    const descriptionLength = document.getElementById('descriptionLength');
    const descriptionWidth = document.getElementById('descriptionWidth');

    titleLength.innerHTML = 0;
    titleLength.classList.remove('text-danger');
    titleWidth.innerHTML = 0;
    titleWidth.classList.remove('text-danger');

    descriptionLength.innerHTML = 0;
    descriptionLength.classList.remove('text-danger');
    descriptionWidth.innerHTML = 0;
    descriptionWidth.classList.remove('text-danger');
}

function displayLoading() {
    document.getElementById('spinner').classList.remove('d-none');
}

function hideLoading() {
    document.getElementById('spinner').classList.add('d-none');
}

function metaFill(response) {
    const titleLength = document.getElementById('titleLength');
    const titleWidth = document.getElementById('titleWidth');
    document.getElementById('titleField').value = response.data.title.text;
    titleLength.innerHTML = response.data.title.titleLength;

    document.getElementById('titleWidth').innerHTML = response.data.title.titleWidth;
    if (response.data.title.titleWidth > titleMaxWidth) {
        titleWidth.classList.add('text-danger');
    } else {
        titleWidth.classList.remove('text-danger');
    }

    document.getElementById('descriptionField').value = response.data.description.text;

    const descriptionLength = document.getElementById('descriptionLength');
    const descriptionWidth = document.getElementById('descriptionWidth');

    descriptionLength.innerHTML = response.data.description.descriptionLength;
    descriptionWidth.innerHTML = response.data.description.descriptionWidth;

    if (response.data.description.descriptionLength > descriptionMaxLength) {
        descriptionLength.classList.add('text-danger');
    } else {
        descriptionLength.classList.remove('text-danger');
    }

    if (response.data.description.descriptionWidth > descriptionMaxWidth) {
        descriptionWidth.classList.add('text-danger');
    } else {
        descriptionWidth.classList.remove('text-danger');
    }
}

function shortText(text, max) {
    if (text.length > max) {
        let shortened = text.substring(0, max);
        return shortened.substr(0, Math.min(shortened.length, shortened.lastIndexOf(" "))).concat(" ...");
    } else {
        return text;
    }
}

function googleSnippet(response) {
    const snippet = document.getElementById('googleSerp');

    snippet.getElementsByClassName("LC20lb")[0].textContent = shortText(response.data.title.text, titleMaxLength);
    snippet.getElementsByClassName("host")[0].textContent = response.data.host;
    snippet.getElementsByClassName("url")[0].textContent = response.data.url;

    document.getElementById('googleDescription').textContent = shortText(response.data.description.text, descriptionMaxLength);
}

function updateTable(url) {
    const d = new Date()
    const date = d.toISOString().split('T')[0];
    const time = d.toTimeString().split(' ')[0];

    const table = document.getElementById('metaResults').getElementsByTagName('tbody')[0];;
    let row = table.insertRow(0);

    let c1 = row.insertCell(0);
    let c2 = row.insertCell(1);

    c1.innerHTML = `<a href="${url}" target="_blank">${url}</a>`;
    c2.innerHTML = `${date} ${time}`

    const rowCount = table.rows.length;
    if (rowCount > 10) {
        table.deleteRow(rowCount - 1);
    }
}



function metaAction(e) {
    e.preventDefault();
    displayLoading();
    let body = document.querySelector("body");

    const params = {
        url: document.getElementById('urlField').value
    }

    const options = {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(params)
    }

    fetch(body.dataset.url + 'meta', options)
        .then(function (response) {
            return response.json();
        })
        .then(function (response) {
            hideLoading();
            console.log(response);

            if (response.error.length === 0) {
                metaFill(response);
                googleSnippet(response);
                updateTable(params.url);
            } else {
                addAlert(response.error);
            }

        });
}

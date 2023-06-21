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

function resetElement(element) {
    element.innerHTML = 0;
    element.classList.remove('text-danger');
}

function getElements() {
    return {
        titleLength: document.getElementById('titleLength'),
        titleWidth: document.getElementById('titleWidth'),
        descriptionLength: document.getElementById('descriptionLength'),
        descriptionWidth: document.getElementById('descriptionWidth')
    };
}

function resetForm(e) {
    e.preventDefault();
    document.getElementById("metaForm").reset();

    const {
        titleLength,
        titleWidth,
        descriptionLength,
        descriptionWidth
    } = getElements();

    resetElement(titleLength);
    resetElement(titleWidth);
    resetElement(descriptionLength);
    resetElement(descriptionWidth);
}

function displayLoading() {
    document.getElementById('spinner').classList.remove('d-none');
}

function hideLoading() {
    document.getElementById('spinner').classList.add('d-none');
}

function metaFill(response) {
    const {
        title: { text: titleText, length: titleLength, width: titleWidth },
        description: { text: descriptionText, length: descriptionLength, width: descriptionWidth }
    } = response.data;

    const setTitleField = document.getElementById('titleField');
    const setDescriptionField = document.getElementById('descriptionField');
    const titleLengthElement = document.getElementById('titleLength');
    const titleWidthElement = document.getElementById('titleWidth');
    const descriptionLengthElement = document.getElementById('descriptionLength');
    const descriptionWidthElement = document.getElementById('descriptionWidth');

    setTitleField.value = titleText;
    titleLengthElement.innerHTML = titleLength;
    titleWidthElement.innerHTML = titleWidth;

    setDescriptionField.value = descriptionText;
    descriptionLengthElement.innerHTML = descriptionLength;
    descriptionWidthElement.innerHTML = descriptionWidth;

    updateElementClass(titleWidthElement, titleWidth, titleMaxWidth);
    updateElementClass(descriptionLengthElement, descriptionLength, descriptionMaxLength);
    updateElementClass(descriptionWidthElement, descriptionWidth, descriptionMaxWidth);
}

function updateElementClass(element, value, maxValue) {
    if (value > maxValue) {
        element.classList.add('text-danger');
    } else {
        element.classList.remove('text-danger');
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

            if (response.error.length === 0) {
                metaFill(response);
                googleSnippet(response);
                updateTable(params.url);
            } else {
                addAlert(response.error);
            }

        });
}

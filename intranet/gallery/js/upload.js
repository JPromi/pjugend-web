const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

//image
async function post_image(apiDomain) {

    document.getElementById('image_upload').disabled = true;

    for (var i = 0; i < image_upload.files.length; i++) {

        const statusTemplate = `
        <tr class="single" data-file="` + i + `">
            <td>
                <div class="loading"></div>
            </td>
            <td>`+image_upload.files[i].name+`</td>
        </tr>
        `;

        document.getElementById('upload-status').innerHTML += statusTemplate;
    }
    
    for (var i = 0; i < image_upload.files.length; i++) {
        //post file
        await post(image_upload.files[i], apiDomain);

        document.querySelectorAll('[data-file="' + i + '"] td div')[0].classList.add('done');
        document.querySelectorAll('[data-file="' + i + '"] td div')[0].classList.remove('loading');
    }

    //clear input
    image_upload.value = "";
    document.getElementById('image_upload').disabled = false;
}

//send post request
async function post(image, apiDomain) {

    let formData = new FormData();
    formData.append("image[]", image);
    formData.append("image_lastmodify[]", image.lastModified / 1000);
    await fetch('https://' + apiDomain + '/int/gallery/image/upload?g=' + urlParams.get('id'), {
        credentials: "include",
        method: "POST",
        body: formData,
    }).then(response => response.json())
    .then(response => console.log(JSON.stringify(response)));
}

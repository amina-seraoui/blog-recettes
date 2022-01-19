const uploads = document.querySelectorAll('.upload.image')
const inputs = document.querySelectorAll('.upload.image input')
const labels = document.querySelectorAll('.upload.image label')

const changeBG = (id, image) => {
    if (image.size > 2000000) {
        window.alert('Image trop lourde')
    } else {
        const reader = new window.FileReader()
        reader.addEventListener('load', () => {
            labels[id].style.background = 'center / cover no-repeat url(' + reader.result + ')'
        })
        reader.readAsDataURL(image)
    }
}

inputs.forEach((input, id) => {
    input.addEventListener('change', e => {
        changeBG(id, e.target.files[0])
    })
})

labels.forEach((label, id) => {
    label.addEventListener('dragover', e => {
        e.preventDefault()
    })
    label.addEventListener('dragenter', e => {
        label.classList.add('drag-over')
    })
    label.addEventListener('dragleave', e => {
        label.classList.remove('drag-over')
    })
    document.addEventListener('drop', e => {
        e.preventDefault()
        label.classList.remove('drag-over')

        inputs[id].files = e.dataTransfer.files
        changeBG(id, e.dataTransfer.files[0])
    })
})

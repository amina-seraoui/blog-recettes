const tasks = document.querySelectorAll('ul.tasks')
if (tasks) {
    tasks.forEach(ul => {
        Array.from(ul.children).forEach(li => {
            li.addEventListener('click', e => {
                li.classList.toggle('complete')
            })
        })
    })
}

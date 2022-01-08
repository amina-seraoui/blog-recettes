const btn = document.getElementById('open-menu')
const close = document.getElementById('close-menu')
const menu = document.getElementById('menu')

if (close && btn && menu) {
    btn.addEventListener('click', e => {
        menu.classList.toggle('opened')
    })

    close.addEventListener('click', e => {
        menu.classList.remove('opened')
    })

    menu.addEventListener('click', e => {
        menu.classList.remove('opened')
    })
}

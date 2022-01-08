const header = document.querySelector('header')

if (header) {
    document.addEventListener('scroll', e => {
        header.setAttribute('data-scroll', window.scrollY)
    })
}

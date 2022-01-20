import React from 'react'
import ReactDOM from 'react-dom'
import { AppContainer } from 'react-hot-loader'

import Stars from './Stars'

const stars = document.getElementsByClassName('js-stars')

if (stars) {
    Array.from(stars).forEach(container => {
        const note = container.getAttribute('data-note')
        const recipe = container.getAttribute('data-recipe')
        ReactDOM.render(
            <AppContainer>
                <Stars stars={note ?? 0} recipe={recipe ?? 0}/>
            </AppContainer>,
            container
        )
    })
}

import React from 'react'
import ReactDOM from 'react-dom'
import { AppContainer } from 'react-hot-loader'

import Stars from './Stars'

const containers = document.getElementsByClassName('js-stars')

if (containers) {
    Array.from(containers).forEach(container => {
        const note = container?.getAttribute('data-note')
        ReactDOM.render(
            <AppContainer>
                <Stars stars={note ?? 0}/>
            </AppContainer>,
            container
        )
    })
}

import React from 'react'
import ReactDOM from 'react-dom'
import { AppContainer } from 'react-hot-loader'

import FieldsetList from './FieldsetList'
import './imageUpload'

const jsTodo = document.getElementsByClassName('js-todo')

if (jsTodo) {
    Array.from(jsTodo).forEach(container => {
        const label = container.getAttribute('data-label')
        const name = container.getAttribute('data-name')
        const data = container.innerText
        ReactDOM.render(
            <AppContainer>
                <FieldsetList label={label} name={name} data={data}/>
            </AppContainer>,
            container
        )
    })
}

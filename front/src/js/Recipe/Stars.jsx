import React, { useState } from 'react'

const Stars = ({ stars, recipe }) => {
    const [note, setNote] = useState(stars)
    const content = []

    const handleClick = note => {
        const body = new window.FormData()
        body.append('note', note)
        body.append('recipe_id', recipe)
        // window.fetch('http://localhost:9000/api/stars', {
        window.fetch('/api/stars', { // => en prod
            method: 'POST',
            body
        })
            .then(res => res.json())
            .then(msg => {
                console.log(msg)
                stars = msg.success?.note ?? stars
                setNote(stars)
            })
            .catch(err => { console.error(err) })
    }

    for (let i = 1; i <= 5; i++) {
        content.push(<span
            key={i}
            className={'star' + (i <= note ? ' fill' : '')}
            onClick={e => handleClick(i)}
        />)
    }

    return content
}

export default Stars

import React from 'react'

const Stars = ({ stars }) => {
    const content = []

    for (let i = 1; i <= 5; i++) {
        content.push(<span key={i} className={'star' + (i <= stars ? ' fill' : '')}/>)
    }

    return content
}

export default Stars

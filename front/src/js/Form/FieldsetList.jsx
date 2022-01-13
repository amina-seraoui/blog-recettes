import React, { useState } from 'react'

const FieldsetList = ({ data, label, name }) => {
    const [value, setValue] = useState('')
    const [finalData, setFinalData] = useState(JSON.parse(data))

    const handleClick = e => {
        if (value.length > 0) {
            addValue(value)
        }
    }
    const handleRemove = id => {
        const clonedData = [...finalData]
        clonedData.splice(id, 1)
        setFinalData(clonedData)
    }

    const addValue = value => {
        const clonedData = [...finalData]
        clonedData.push(value)
        setFinalData(clonedData)
        setValue('')
    }

    return <>
        <legend>{label}</legend>
        <div className="field">
            <input type="text" value={value} onInput={e => setValue(e.target.value)}/>
            <span className="btn secondary" onClick={handleClick}>+</span>
        </div>

        <ol className="preview">
            {
                finalData.map((item, id) => {
                    return <li key={id}>
                        {item}
                        <span className="btn danger" onClick={e => handleRemove(id)}>-</span>
                    </li>
                })
            }
        </ol>
        <input name={name} type="hidden" value={JSON.stringify(finalData)} readOnly={true} />
    </>
}

export default FieldsetList

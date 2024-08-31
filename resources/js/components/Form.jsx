import { useState } from "react";

export const Form = () => {
    const [isLoading, setIsLoading] = useState(false);

    const onSubmit = (e) => {
        setIsLoading(true);

        e.preventDefault()
        const formData = new FormData(e.target)
        const video = formData.get('video')
        const data = new FormData()
        data.append('video', video)
        fetch('/converter', {
            method: 'POST',
            body: data,
        }).then((resp) => {
            setIsLoading(false);
            // Reset form
            e.target.reset()
            if (resp.ok) {
                alert('¡Video convertido con éxito!')
            } else {
                alert('¡Error al convertir el video!')
            }
        }).catch((err) => {
            setIsLoading(false);
            alert('¡Error al convertir el video!')
        });
    }
    return (
        <form id="form" onSubmit={ onSubmit } className={'form-group'}>
            <input type="file" name="video" className={'form-control-file'} />
            <button type="submit" className={ isLoading ? 'btn-disabled' : 'btn-primary'} disabled={isLoading}>
                { isLoading ? 'Procesando...' : 'Convertir' }
            </button>
        </form>
    )
}

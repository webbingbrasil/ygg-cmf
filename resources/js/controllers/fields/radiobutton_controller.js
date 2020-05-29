import { Controller } from 'stimulus';

export default class extends Controller {
    /**
     *
     */
    checked(event) {
        event.target.offsetParent.querySelectorAll('input').forEach((input)=>{
            input.removeAttribute('checked');
        });

        event.target.querySelector('input').setAttribute('checked', 'checked');
    }
}

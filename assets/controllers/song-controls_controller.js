import { Controller } from '@hotwired/stimulus';
import axios from "axios";

export default class extends Controller {

    static values = {
        apiUrl: String
    };

    play(e) {
        e.preventDefault()
        axios.get(this.apiUrlValue).then(response => {
            const audio = new Audio(response.data.url)
            audio.play()
        })
    }
}
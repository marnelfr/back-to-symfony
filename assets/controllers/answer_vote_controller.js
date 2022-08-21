import { Controller } from '@hotwired/stimulus';
import axios from "axios";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
export default class extends Controller {
    static values = {
        apiUrl: String
    }

    vote(e) {
        e.preventDefault()
        console.log('okoko')
        // axios.post(this.infoApiUrl)
    }

}

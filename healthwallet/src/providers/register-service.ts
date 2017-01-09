import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/operator/map';

/*
  Generated class for the RegisterService provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/
@Injectable()
export class RegisterService {

	data={};

	constructor(public http: Http) {
		console.log('Hello RegisterService Provider');
	}

	/*findAll() {
        return this.http.get("https://randomuser.me/api/")
            .map(res => res.json())
            .catch(this.handleError);
    }
    handleError(error) {
        console.error(error);
        return Observable.throw(error.json().error || 'Server error');
    }
	this.http.get('https://randomuser.me/api/?results=3')
			.map(res => res.json())
			.subscribe(data => {
				// we've got back the raw data, now generate the core schedule data
				// and save the data for later reference
				this.data = data.results;
				resolve(this.data);
			});
    */

    register(uicstar) {
		/*if (this.data) {
			// already loaded data
			return Promise.resolve(this.data);
		}*/
		
		let headers = new Headers({ 'Content-Type': 'application/json' });
	    let body = new FormData();
	    body.append('UIC', uicstar);

		// postd = "email=chessell@maroonstudios.com&password=maroon&authtype=basic";
		// don't have the data yet
		return new Promise(resolve => {
			// We're using Angular HTTP provider to request the data,
			// then on the response, it'll map the JSON data to a parsed JS object.
			// Next, we process the data and resolve the promise with the new data.
			// this.http.post('http://ec2-54-255-166-23.ap-southeast-1.compute.amazonaws.com/api/login', postd)
			this.http.post('http://54.254.135.23:5000/register/healthwallet', body, headers)
			.map(res => res.json())
			.subscribe(data => {
				// we've got back the raw data, now generate the core schedule data
				// and save the data for later reference
				this.data = data;
				resolve(this.data);
			}, error => {
	            console.log("Oooops!", error);
	        });
		});
	}
}

import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/delay';
import 'rxjs/add/operator/retrywhen';
import 'rxjs/add/operator/timeout';

/*
  Generated class for the AccessRequest provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular 2 DI.
*/

const SERVER_URL = 'http://54.254.135.23:5000';

@Injectable()
export class AccessRequest {

	data=[];

	constructor(public http: Http) {
		console.log('Hello AccessRequest Provider');
	}

	retrieve(uicstar) {
		let headers = new Headers({ 'Content-Type': 'application/json' });
	    let body = new FormData();
	    body.append('UIC', uicstar);

		return new Promise(resolve => {

			this.http.post(SERVER_URL + '/search/request', body, headers)
			.map(res => res.json())
			.retryWhen(error => error.delay(2000))
			.subscribe(data => {

				this.data = data;
				resolve(this.data);
			}, error => {
	            console.log("Oooops!", error);
	        });
		});
	}

	approve(request) {
		let headers = new Headers({ 'Content-Type': 'application/json' });
	    let body = new FormData();
	    body.append('UIC', request.uic);
	    body.append('recv_hpid', request.recv_hpid);
	    body.append('recv_hpid_pubkey', request.recv_hpid_pubkey);
	    body.append('send_hpid', request.send_hpid);
	    body.append('wallet_address', request.wallet_address);
	    body.append('approve_status', request.approve_status);

		return new Promise(resolve => {

			this.http.post(SERVER_URL + '/add/request/approve', body, headers)
			.map(res => res.json())
			.retryWhen(error => error.delay(2000))
			.subscribe(data => {
				
				this.data = data;
				resolve(this.data);
			}, error => {
	            console.log("Oooops!", error);
	        });
		});
	}
}

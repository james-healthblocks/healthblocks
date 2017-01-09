import { Component } from '@angular/core';
import { NavController, NavParams, Refresher } from 'ionic-angular';
import { Data } from '../../providers/data';

import { AccessRequest } from '../../providers/access-request';

@Component({
	selector: 'page-accessmanager',
	templateUrl: 'accessmanager.html',
	providers: [AccessRequest]
})
export class AccessManager{

	pending = [];
	approved = [];
	UICstar: string;
	loading: boolean;
	refresher: Refresher;

	constructor(public navCtrl: NavController, public navParams: NavParams, public dataService: Data, public requestService: AccessRequest) {
		//this.UICstar = "JDJD0101012000";
		this.dataService.getUIC().then((uic) => {
			if(uic){
				this.UICstar = uic;
			}
			this.refresh();
		});
		// this.dataService.setWalletAddress("1BUvfJAvoS2REXRfCdMtkE3Mv9nCq7SFQ6ZRqS");
		
	}

	refresh() {
		this.loading = true;
		this.pending = [];
		this.approved = [];

		this.requestService.retrieve(this.UICstar)
		.then(data => {
			let parsed = JSON.parse(JSON.stringify(data));

			console.log("ACCESS DATA", data, parsed.status, parsed.results);
			this.loading = false;
			if (parsed.results) {
				let requests = parsed.results;

				for(let req of requests) {
					if (req.approve_status.toUpperCase() == "TRUE") {
						this.approved.push(req);
					}
					else if (req.approve_status.toUpperCase() == "FALSE") {
						this.pending.push(req);
					}
				}
				
				console.log("approved", this.approved, "pending", this.pending);
			}
			else if (parsed.status === "failed") {
				console.log("status: failed")
			}
			if (this.refresher) {
				this.refresher.complete();
			}
		}, error => {
			console.log("status: error");
		});
	}

	doRefresh(refresher) {
		this.refresher = refresher;
		this.refresh();
	}

	approve(request, status) {

		this.dataService.getWalletAddress().then((wallet_address) => {
			request.wallet_address = wallet_address;
			let isDenied = request.approve_status.toUpperCase() == "FALSE" && !status;
			request.approve_status = status == true ? "TRUE" : "FALSE";
			console.log(wallet_address, status);
			console.log("ACCESSMANAGER REQUEST", request);
			// this.loading = true;

			if (!isDenied) {
				this.requestService.approve(request)
				.then(data => {
					let parsed = JSON.parse(JSON.stringify(data));

					console.log("ACCESS DATA", data, parsed.status, parsed.results);
					this.loading = false;
					if (parsed.status === "failed") {
						console.log("status: failed")
					}

					this.refresh();
					
				}, error => {
					console.log("status: error");
				});
			}
		});

	}

	ionViewDidLoad() {
		console.log('ionViewDidLoad AccessManager');
	}

}

import { Component, ViewChild } from '@angular/core';
import { Nav, Platform } from 'ionic-angular';
import { StatusBar, Splashscreen } from 'ionic-native';
import { Storage } from '@ionic/storage';
import { Blankpage } from '../pages/blankpage/blankpage';
import { Registration } from '../pages/registration/registration';
import { Landing } from '../pages/landing/landing';
import { Profile } from '../pages/profile/profile';
import { AccessManager } from '../pages/accessmanager/accessmanager';
import { Settings } from '../pages/settings/settings';

@Component({
  templateUrl: 'app.html'
})
export class MyApp {
  @ViewChild(Nav) nav: Nav;

    rootPage: any = Blankpage;

  pages: Array<{title: string, component: any}>;

  storage:any;
  
  constructor(public platform: Platform) {
    this.initializeApp();

    // used for an example of ngFor and navigation
    this.pages = [
      { title: 'Home', component: Landing },
      { title: 'Profile', component: Profile },
  	  { title: 'Access Manager', component: AccessManager },
  	  { title: 'Settings', component: Settings }
    ];

  }

  initializeApp() {
    this.platform.ready().then(() => {
      // Okay, so the platform is ready and our plugins are available.
      // Here you can do any higher level native things you might need.
      StatusBar.styleDefault();
      Splashscreen.hide();
      
	    this.storage = new Storage();;
    });
  }

  openPage(page) {
    // Reset the content nav to have just this page
    // we wouldn't want the back button to show in this scenario
    this.nav.setRoot(page.component);
  }
}

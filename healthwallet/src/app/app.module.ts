import { NgModule, ErrorHandler } from '@angular/core';
import { IonicApp, IonicModule, IonicErrorHandler } from 'ionic-angular';
import { MyApp } from './app.component';
import { Data } from '../providers/data';
import { DatePipe } from '@angular/common';
import { Registration } from '../pages/registration/registration';
import { Landing } from '../pages/landing/landing';
import { Profile } from '../pages/profile/profile';
import { Insurance } from '../pages/insurance/insurance';
import { AccessManager } from '../pages/accessmanager/accessmanager';
import { Settings } from '../pages/settings/settings';


@NgModule({
  declarations: [
    MyApp,
    Registration,
    Landing,
  	Profile,
  	Insurance,
  	AccessManager,
  	Settings
  ],
  imports: [
    IonicModule.forRoot(MyApp)
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    Registration,
    Landing,
  	Profile,
  	Insurance,
  	AccessManager,
  	Settings
  ],
  providers: [
	Storage, Data, DatePipe,
	{provide: ErrorHandler, useClass: IonicErrorHandler}]
})
export class AppModule {}

import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { routing } from './app.routing';
import { ContactComponent } from './contact/contact.component';
import { AboutComponent } from './about/about.component';
import { CareersComponent } from './careers/careers.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { HomeComponent } from './home/home.component';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';

@NgModule({
  declarations: [
    AppComponent,
    ContactComponent,
	AboutComponent,
	SidebarComponent,
	HomeComponent,
	CareersComponent,
	HeaderComponent,
	FooterComponent
  ],
  imports: [
    BrowserModule,
	routing
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }


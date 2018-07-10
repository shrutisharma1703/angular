import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-contact',
  templateUrl: './contact.component.html',
  styleUrls: ['./contact.component.scss']
})
export class ContactComponent implements OnInit {

  constructor() { }
  COMING_SOON_LOGO = require("../../assets/co.jpg");
  
  ngOnInit() {
  }

}

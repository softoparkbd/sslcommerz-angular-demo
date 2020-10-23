import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Form } from './Form';
import { $ } from 'protractor';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title = 'ng-frontend';
  form: Form;
  submitted = false;

  constructor(private http: HttpClient) {}

  onSubmit(data) {
    this.submitted = true;
    console.warn(data);
    this.http
      .post('http://sslapi.local/pay-via-ajax', data)
      .subscribe((result) => {
        console.warn('result', result);
      });
  }

  ngOnInit() {
    // Make the http request:
    this.http.get<Form>(`http://sslapi.local/api/items`).subscribe((data) => {
      console.log(data);
      this.form = data;
    });
  }
}

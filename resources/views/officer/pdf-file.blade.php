<!DOCTYPE html>
<html>
<head>
    <title>General Plan of Activities</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf-file.css') }}">
</head>
<body>
    
    <div class="page">
        <!-- Define header and footer blocks before your content -->
        <header>
            <div class="left-div">
                <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                
            </div>
            <div class="right-div">
                    <small>Republic of the Philippines   </small> 
                    <p> POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                    <p>Office of the Vice President for Branches and Satellite Campuses</p>
                    <p>TAGUIG BRANCH</p>
                    <p>Office of Student Services</p>
                    <p style="text-transform: uppercase;">{{ $organization->organization_name }}</p>
            </div>
        </header>
        <hr>
        <h4 class="text-center">
            GENERAL PLAN OF ACTIVITIES  <br>
            @if ($inputSem == '1st Semester')
                FIRST SEMESTER
            @else
                SECOND SEMESTER
            @endif
            <br>
            ACADEMIC YEAR {{ $inputYear }}

        </h4>
        <div>        
            @if (isset($upcoming_events))
                <table class="main-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Name/Title of Activity</th>
                            <th>Objectives</th>
                            <th>Participant(s)/ Beneficiary(ies)
                                (indicate the number)</th>
                            <th>Head Organization</th>
                            <th>In partnership w/ (if there is any)</th>
                            <th>Venue & time</th>
                            <th>Type of Activity</th>
                            <th>Projected Budget</th>
                            <th>Fund Sourcing
                                Org. Fund / Sponsor
                                (Pls. indicate)
                                </th>
                            <th>Remarks</th>               
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            $data = 0
                        @endphp
                        @if ($upcoming_events->isNotEmpty())
                            @foreach ($upcoming_events as $upcoming_event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date_format(date_create($upcoming_event->date), 'F d, Y') }}</td>
                                    <td>{{ $upcoming_event->title }}</td>
                                    <td>{{ $upcoming_event->objectives }}</td>
                                    <td>{{ $upcoming_event->participants }}</td>
                                    <td>{{ $upcoming_event->head_organization }}</td>
                                    <td>{{ $upcoming_event->partnerships }}</td>
                                    <td>{{ $upcoming_event->venue }} / {{ date_format(date_create($upcoming_event->time), 'H : i a')}}</td>
                                    <td>{{ $upcoming_event->activity_type }}</td>
                                    <td>{{ $upcoming_event->projected_budget }}</td>
                                    <td>{{ $upcoming_event->fund_source }} / {{ $upcoming_event->sponsor }}</td>
                                    <td></td>
                                    
                                </tr>
                               @php
                                   $data++;
                               @endphp
                                @if ($data == 3)

                                </tbody>
                                </table>
                                <footer>
                                    <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                                         
                                     <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
                         
                                         <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>

                                </footer>
                                @if (!$loop->last)
                                <div class="page-break"></div>
                                <header>
                                    <div class="left-div">
                                        <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                                        <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                                        
                                    </div>
                                    <div class="right-div">
                                            <small>Republic of the Philippines   </small> 
                                            <p> POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                                            <p>Office of the Vice President for Branches and Satellite Campuses</p>
                                            <p>TAGUIG BRANCH</p>
                                            <p>Office of Student Services</p>
                                            <p>COMPUTER SOCIETY</p>
                                    </div>
                                </header>
                                <hr>
                                <table class="main-table">
                                     <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Name/Title of Activity</th>
                                            <th>Objectives</th>
                                            <th>Participant(s)/ Beneficiary(ies)
                                                (indicate the number)</th>
                                            <th>Head Organization</th>
                                            <th>In partnership w/ (if there is any)</th>
                                            <th>Venue & time</th>
                                            <th>Type of Activity</th>
                                            <th>Projected Budget</th>
                                            <th>Fund Sourcing
                                                Org. Fund / Sponsor
                                                (Pls. indicate)
                                                </th>
                                            <th>Remarks</th>               
                                        </tr>
                                    </thead>
                                <tbody class="text-center">
                                @endif

                                              
                                    
                                    @php
                                        $data=0;
                                    @endphp
                                @endif

                            @endforeach
                           
                        @else
                        <tr><td colspan="12">No results found!</td></tr>
                        @endif
                    </tbody>
                </table>
                
            @endif
        </div>    
        <footer>
          
            <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                 
             <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
 
                 <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>
 
            
         </footer>
         
    </div>
    <div class="page-break"></div>
    <div class="last-page">
        <header>
            <div class="left-div">
                <img src="{{ public_path('images/pup-logo-Polytechnic_University_of_the_Philippines.png') }}" style="width: 100px; height: 100px">
                <img src="{{ public_path('/storage/'. $organization->logo->file) }}" style="width: 100px; height: 100px">
                
            </div>
            <div class="right-div">
                    <small>Republic of the Philippines   </small> 
                    <p>POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>  
                    <p>Office of the Vice President for Branches and Satellite Campuses</p>
                    <p>TAGUIG BRANCH</p>
                    <p>Office of Student Services</p>
                    <p style="text-transform: uppercase;">{{ $organization->organization_name }}</p>
            </div>
        </header>
        <hr>

        <h4 class="text-center">
           BREAKDOWN OF FEES (Membership Fee)
        </h4>
        <table class="breakdown-table"> 
            <tr>
                <th>REQUIRED</th>
                <TH>AMOUNT</TH>
            </tr> 
            <tr>
                <td>Membership Fee</td>
                <td>P {{ $inputMembershipfee }}.00</td>
            </tr>
            <tr>   
                <td>Total Collection</td>
                <td>P {{ $inputCollection }}.00</td>               
            </tr>
        </table>
            <div class="signatures">
                <div class="prepared">
                    <p>Prepared by:</p>
                    @if ($president_signature != null)
                        @if ($president_signature->signature_path != null)
                            <img src="{{ public_path('signatures/'. $president_signature->signature_path) }}" style="width: 100px; height: 60px; margin-top: 5px;"alt="signature">
                            <span style="font-style: bold;">{{ $president_signature->user->first_name }} {{ $president_signature->user->middle_name }} {{ $president_signature->user->last_name }}</span>
                            <span style="font-style: italic;">President, {{ $organization->organization_name }}	</span>
                        @else
                            <img src="" style="width: 100px; height: 60px; margin-top: 5px;"alt="signature">
                            <span style="font-style: bold;">{{ $president_signature->user->first_name }} {{ $president_signature->user->middle_name }} {{ $president_signature->user->last_name }}</span>
                            <span style="font-style: italic;">President, {{ $organization->organization_name }}	</span>
                        @endif
                        
                    @else
                    <div style="width: 100px; height: 60px; margin-top: 5px;"></div>
                    <span style="font-style: italic; color: red;"> No faculty member registered</span>
                        <span style="font-style: italic;">President, {{ $organization->organization_name }}</span>
                    @endif
                    
                </div>
                <div class="approved">  
                    <p>Approved by:</p>
                   
                    @if ($admin_signature != null)
                        @if ($admin_signature->signature_path != null)
                            @if ($admin_signature->user->title != null)
                                <img src="{{ public_path('signatures/'. $admin_signature->signature_path) }}" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                                <span style="font-style: bold;">{{ $admin_signature->user->title.'. ' }}{{ $admin_signature->user->first_name }} {{ $admin_signature->user->middle_name }} {{ $admin_signature->user->last_name }}</span>
                                <span style="font-style: italic;">Head of Student Services</span>
                            @else
                                <img src="{{ public_path('signatures/'. $admin_signature->signature_path) }}" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                                <span style="font-style: bold;">{{ $admin_signature->user->first_name }} {{ $admin_signature->user->middle_name }} {{ $admin_signature->user->last_name }}</span>
                                <span style="font-style: italic;">Head of Student Services</span>   
                            @endif
                       
                        @else
                            <img src="" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                            <span style="font-style: bold;">{{ $admin_signature->user->title.'. ' }}{{ $admin_signature->user->first_name }} {{ $admin_signature->user->middle_name }} {{ $admin_signature->user->last_name }}</span>
                            <span style="font-style: italic;">Head of Student Services</span>
                        @endif
                    @else
                    <div style="width: 100px; height: 60px; margin-top: 5px;"></div>
                    <span style="font-style: italic; color: red;"> No faculty member registered</span>
                            <span style="font-style: italic;">Head of Student Services</span>
                    @endif
                </div>
                <div class="adviser">
                    <p>Noted by:</p>
                    @if ($adviser_signature != null)
                        @if ($adviser_signature->signature_path != null)
                            @if ($adviser_signature->user->title != null)
                                <img src="{{ public_path('signatures/'. $adviser_signature->signature_path) }}" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                                <span style="font-style: bold;">{{ $adviser_signature->user->title.'. ' }}{{ $adviser_signature->user->first_name }} {{ $adviser_signature->user->middle_name }} {{ $adviser_signature->user->last_name }}</span>
                                <span style="font-style: italic;">Adviser, {{ $organization->organization_name }}</span>
                            @else
                                <img src="{{ public_path('signatures/'. $adviser_signature->signature_path) }}" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                                <span style="font-style: bold;">{{ $adviser_signature->user->first_name }} {{ $adviser_signature->user->middle_name }} {{ $adviser_signature->user->last_name }}</span>
                                <span style="font-style: italic;">Adviser, {{ $organization->organization_name }}</span>
                            @endif
                        @else
                            <img src="" style="width: 100px; height: 60px; margin-top: 5px;" alt="signature">
                            <span style="font-style: bold;">{{ $admin_signature->user->title.'. ' }}{{ $adviser_signature->user->first_name }} {{ $adviser_signature->user->middle_name }} {{ $adviser_signature->user->last_name }}</span>
                            <span style="font-style: italic;">Adviser, {{ $organization->organization_name }}</span>
                        @endif
                       
                    @else
                        <div style="width: 100px; height: 60px; margin-top: 5px;"></div>
                        <span style="font-style: italic; color: red;"> No faculty member registered</span>
                        <span style="font-style: italic;">Adviser</span>
                    @endif
                    
                </div>
                <div class="director">
                    <span style="font-style: bold;">Dr. Marissa B. Ferrer</span>
                    <span style="font-style: italic;">Director</span>
                </div>
            </div>
        
       
        <footer>
          
            <p class="footer-text">Gen. Santos Ave. Lower Bicutan, Taguig City 1772; (Direct Line) 837-5858 to 60; (Telefax) 837-5859;</p> 
                 
             <p>website: www.pup.edu.ph     e-mail: taguig@pup.edu.ph </p>
 
                 <p>“THE COUNTRY’S 1st POLYTECHNIC UNIVERSITY”</p>
 
            
         </footer>
    </div>
</body>
</html>
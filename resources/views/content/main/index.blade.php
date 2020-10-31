@extends('layout.master')


@section('content')

<!-- Navbar Section -->
@include('content.main.fragment.app_navbar')

<!-- Activity Schedule Section -->
<div class="col">
    <div class="row">
        <div class="col-12">
            <div style="float: right;">
                <button type="button" class="btn btn-success" id="add-button">
                    <i class="fa fa-plus mr-1"></i> Tambah Data
                </button>
            </div>
        </div>
    </div>  
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
            <table class="table table-bordered" id="activity">
              <thead class="bg-white" align="center">
                <tr>
                  <th width="10px;">Metode</th>
                  <th class="month">-</th>
                  <th class="month">-</th>
                  <th class="month">-</th>
                  <th class="month">-</th>
                  <th class="month">-</th>
                  <th class="month">-</th>                  
                </tr>
              </thead>
              <tbody id="activity-list-container">
                <tr>
                  <th class="bg-white text-center methods">-</th>
                  <td class="month"></td>
                  <td class="month"></td>
                  <td class="month"></td>
                  <td class="month"></td>
                  <td class="month"></td>
                  <td class="month"></td>                    
                </tr>
              </tbody>
            </table>                 
            </div>                   
        </div>
    </div>
</div>

<!-- Activity Modal Section -->
@include('content.main.fragment.activity_modal')

@endsection


@section('script')
<link rel="stylesheet" href="{{ asset('css/index.css?'.uniqid()) }}"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous"></script>

<script type="text/javascript" src="{{ asset('js/index.js?'.uniqid()) }}"></script>
@endsection
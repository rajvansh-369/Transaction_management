<div class="container-fluid px-1 py-5 mx-auto">
    <div class="row d-flex justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7">
            <div class="card b-0">
                <h3 class="heading">{{ $custName ?? 'Customer Name' }}</h3>
                <p class="desc">Total Amount: <span class="red-text">₹
                        {{ number_format($totalSumAmount + $totalSumIntrest, 2, '.', ',') }}</span>
                <p class="desc">Total Due: <span class="red-text">₹
                        {{ number_format($totalSumDue, 2, '.', ',') }}</span></p>

                @if ($totalSumDue == 0 && count($getInvoices ) > 0)
                    <h4 class="desc" style="color: red">Already Paid</h4>
                @endif

                <ul id="progressbar" class="text-center">
                    <li class="active step0" id="step1"></li>
                    <li class="step0  {{ $activeClass ?? ' ' }}" id="step2"></li>
                    <li class="step0 {{ $secondPage ? 'active' : '' }} " id="step3"></li>
                    <li class="step0 {{ $secondPage ? 'active' : '' }}" id="step4"></li>
                </ul>
                @if ($firstPage)
                    <fieldset class="show">

                        <div class="form-card">
                            <form wire:submit.prevent="adjustAmount">



                                <h5 class="sub-heading">Select Service(s)</h5>

                                {{-- @dd($customers) --}}
                                <select class="form-control" id="langOpt" wire:model="custOpt"
                                    wire:click="getInvoice">
                                    <option value="">Select Customer Name</option>

                                    @forelse ($customers as  $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>

                                    @empty
                                    @endforelse

                                </select>
                                <div class="row px-1 radio-group">


                                    @forelse ($getInvoices as  $getInvoice)
                                        <div class="card-block text-center radio selected" style="font-size: 13px">
                                            <div class="image-icon">
                                               <a href="{{route('pdf', $getInvoice)}}" target="_blank"><img class="icon icon1" src="{{ asset('invoice.png') }}"></a>
                                            </div>
                                            <p class="sub-desc">Bill No : #{{ $getInvoice->bill_no }}</p>
                                            <p class="sub-desc"> Total Due : ₹{{ $getInvoice->total_due }}</p>
                                            <p class="sub-desc"> Total Amount : ₹{{ $getInvoice->total_amount }}</p>
                                            <p class="sub-desc">Invoice Date :
                                                {{ \Carbon\Carbon::parse($getInvoice->invoice_date)->format('d-M-Y') }}
                                            </p>
                                            <input type="checkbox" wire:model.defer="invoice"
                                                value="{{ $getInvoice->id }}">
                                        </div>
                                    @empty
                                        {{-- <div > --}}

                                        <p class="text-center ">No Ledger Found</p>
                                        {{-- </div> --}}
                                        {{-- <div class="card-block text-center radio selected">
                                        <div class="image-icon">
                                            <img class="icon icon1" src="{{ asset('invoice.png') }}">
                                        </div>
                                        <p class="sub-desc">#{{$getInvoice->bill_no}}</p>

                                    </div> --}}
                                    @endforelse

                                    <button id="next1" class="btn-block btn-primary mt-3 mb-1 next">NEXT<span
                                            class="fa fa-long-arrow-right"></span></button>

                            </form>
                        </div>

                    </fieldset>
                @endif

                @if ($secondPage)
                    <fieldset class="show">
                        <div class="form-card">
                            <h5 class="sub-heading mb-4">Success!</h5>
                            <p class="message">Thank You for choosing our website.<br>Quotation will be sent to your
                                Email ID and Contact Number.</p>
                            <div class="check">
                                <img class="fit-image check-img" src="{{ asset('success.gif') }}">
                            </div>
                        </div>
                    </fieldset>
                @endif

            </div>
        </div>
    </div>
</div>

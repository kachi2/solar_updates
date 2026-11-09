{{--
    Add / edit delivery address form.
    Usage: @include('includes.address-form', ['address' => $address /* null when adding */, 'isFirst' => bool])
--}}
@php
    $editing = $address !== null;
    $isDefault = $editing && (int) $address->is_default === 1;
    $states = ['Abia', 'Abuja (FCT)', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
        'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano',
        'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo',
        'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'];
@endphp
<form method="POST" action="{{ route('address.store', $editing ? $address->id : auth()->id()) }}" class="sofar-form" novalidate>
    @csrf
    @if ($editing)
        <input type="hidden" name="update" value="1">
    @endif

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="addrName">Receiver's full name</label>
            <input type="text" id="addrName" name="name" autocomplete="name" required
                   value="{{ old('name', $address->receiver_name ?? auth()->user()->name) }}"
                   @class(['form-control', 'is-invalid' => $errors->has('name')])>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-6">
            <label for="addrPhone">Phone number</label>
            <input type="tel" id="addrPhone" name="phone" autocomplete="tel" required placeholder="0803 000 0000"
                   value="{{ old('phone', $address->receiver_phone ?? auth()->user()->phone) }}"
                   @class(['form-control', 'is-invalid' => $errors->has('phone')])>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-group">
        <label for="addrStreet">Street address</label>
        <input type="text" id="addrStreet" name="address" autocomplete="street-address" required
               placeholder="House number, street, area / landmark"
               value="{{ old('address', $address->address ?? '') }}"
               @class(['form-control', 'is-invalid' => $errors->has('address')])>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="addrCity">City / town</label>
            <input type="text" id="addrCity" name="city" autocomplete="address-level2"
                   value="{{ old('city', $address->city ?? '') }}"
                   @class(['form-control', 'is-invalid' => $errors->has('city')])>
            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-6">
            <label for="addrState">State</label>
            <input type="text" id="addrState" name="state" list="nigerianStates" autocomplete="address-level1"
                   value="{{ old('state', $address->state ?? '') }}"
                   @class(['form-control', 'is-invalid' => $errors->has('state')])>
            <datalist id="nigerianStates">
                @foreach ($states as $state)
                    <option value="{{ $state }}">
                @endforeach
            </datalist>
            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="form-group">
        <label for="addrEmail">Email <span class="sofar-form__optional">(optional)</span></label>
        <input type="email" id="addrEmail" name="email" autocomplete="email"
               value="{{ old('email', $address->receiver_email ?? auth()->user()->email) }}"
               @class(['form-control', 'is-invalid' => $errors->has('email')])>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if ($isDefault)
        <p class="sofar-form__note">This is your default delivery address. To change the default, use <em>Set as default</em> on another address.</p>
    @else
        <div class="custom-control custom-checkbox sofar-form__check">
            <input type="checkbox" class="custom-control-input" id="addrDefault" name="default" value="1"
                   @checked(old('default', $isFirst ?? false))>
            <label class="custom-control-label" for="addrDefault">Use as my default delivery address</label>
        </div>
    @endif

    <div class="sofar-form__actions">
        <button type="submit" class="sofar-cart-btn">{{ $editing ? 'Save changes' : 'Save address' }}</button>
        <a href="{{ route('users.address') }}" class="sofar-checkout-step__secondary sofar-account__browse">Cancel</a>
        @if ($editing && ($canRemove ?? false))
            <a href="{{ route('address.delete', encrypt($address->id)) }}" class="sofar-form__danger js-confirm" data-confirm="Remove this address?">Remove this address</a>
        @endif
    </div>
</form>

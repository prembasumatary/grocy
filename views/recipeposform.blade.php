@extends('layout.default')

@if($mode == 'edit')
	@section('title', $L('Edit recipe ingredient'))
@else
	@section('title', $L('Add recipe ingredient'))
@endif

@section('viewJsName', 'recipeposform')

@section('content')
<div class="row">
	<div class="col-xs-12 col-md-6 col-xl-5 pb-3">
		<h1>@yield('title')</h1>
		<h3 class="text-muted">{{ $L('Recipe') }} <strong>{{ $recipe->name }}</strong></h3>

		<script>
			Grocy.EditMode = '{{ $mode }}';
			Grocy.EditObjectParentId = {{ $recipe->id }};
		</script>

		@if($mode == 'edit')
			<script>Grocy.EditObjectId = {{ $recipePos->id }};</script>
		@endif

		<form id="recipe-pos-form" novalidate>

			@php $prefillByName = ''; if($mode=='edit') { $prefillByName = FindObjectInArrayByPropertyValue($products, 'id', $recipePos->product_id)->name; } @endphp
			@include('components.productpicker', array(
				'products' => $products,
				'nextInputSelector' => '#amount',
				'prefillByName' => $prefillByName
			))

			<div class="form-group row">
				<div class="col">
					<div class="row">

						@php if($mode == 'edit') { $value = $recipePos->amount; } else { $value = 1; } @endphp
						@include('components.numberpicker', array(
							'id' => 'amount',
							'label' => 'Amount',
							'min' => 0,
							'value' => $value,
							'invalidFeedback' => $L('This cannot be negative and must be an integral number'),
							'additionalGroupCssClasses' => 'col-4'
						))

						<div class="form-group col-8">
							<label for="qu_id">{{ $L('Quantity unit') }}</label>
							<select required @if($mode == 'create' || ($mode == 'edit' && $recipePos->only_check_single_unit_in_stock != 1)) disabled @endif class="form-control" id="qu_id" name="qu_id">
								@foreach($quantityUnits as $quantityunit)
									<option @if($mode == 'edit' && $quantityunit->id == $recipePos->qu_id) selected @endif value="{{ $quantityunit->id }}">{{ $quantityunit->name }}</option>
								@endforeach
							</select>
							<div class="invalid-feedback">{{ $L('A quantity unit is required') }}</div>
						</div>

					</div>
					<div class="row">
						<div class="col">
							<div class="form-check">
								<input type="hidden" name="only_check_single_unit_in_stock" value="0">
								<input @if($mode == 'edit' && $recipePos->only_check_single_unit_in_stock == 1) checked @endif class="form-check-input" type="checkbox" id="only_check_single_unit_in_stock" name="only_check_single_unit_in_stock" value="1">
								<label class="form-check-label" for="only_check_single_unit_in_stock">{{ $L('Only check if a single unit is in stock (a different quantity can then be used above)') }}</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-check mb-3">
				<input type="hidden" name="not_check_stock_fulfillment" value="0">
				<input @if($mode == 'edit' && $recipePos->not_check_stock_fulfillment == 1) checked @endif class="form-check-input" type="checkbox" id="not_check_stock_fulfillment" name="not_check_stock_fulfillment" value="1">
				<label class="form-check-label" for="not_check_stock_fulfillment">{{ $L('Disable stock fulfillment checking for this ingredient') }}</label>
			</div>

			<div class="form-group">
				<label for="ingredient_group">{{ $L('Group') }}&nbsp;&nbsp;<span class="small text-muted">{{ $L('This will be used as a headline to group ingredients together') }}</span></label>
				<input type="text" class="form-control" id="ingredient_group" name="ingredient_group" value="@if($mode == 'edit'){{ $recipePos->ingredient_group }}@endif">
			</div>

			<div class="form-group">
				<label for="note">{{ $L('Note') }}</label>
				<textarea class="form-control" rows="2" id="note" name="note">@if($mode == 'edit'){{ $recipePos->note }}@endif</textarea>
			</div>

			<button id="save-recipe-pos-button" class="btn btn-success">{{ $L('Save') }}</button>

		</form>
	</div>

	<div class="col-xs-12 col-md-6 col-xl-4">
		@include('components.productcard')
	</div>
</div>
@stop

<!-- Add Portfolio Modal -->
<div class="modal fade" id="addPortfolioModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="portfolioParentDiv" class="form-group">
                    <label for="portfolioParent">Top Portfolio</label>
                    <select id="portfolioParent" class="form-control modal-input selectpicker">
                        @role('super-admin')
                            <option id-parent="0" selected>Top Portfolio</option>
                        @else
                            <option selected>Choose parent portfolio...</option>
                        @endrole
                        @foreach($parentPortfolios as $item)
                            <option id-parent="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="portfolioNameDiv" class="form-group">
                    <label for="portfolioName">Name</label>
                    <input type="text" class="form-control modal-input" id="portfolioName">
                </div>
                <div id="portfolioDescriptionDiv" class="form-group">
                    <label for="portfolioDescription">Description</label>
                    <textarea type="text" class="form-control modal-input" id="portfolioDescription"></textarea>
                </div>
                <div id="portfolioCurrencyDiv" class="form-group">
                    <label for="portfolioCurrency">Currency</label>
                    <select id="portfolioCurrency" class="form-control modal-input selectpicker">
                        <option selected>Choose currency...</option>
                        @foreach($currency as $item)
                            <option id-currency="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="portfolioAllocationMinDiv" class="form-group">
                    <label for="portfolioAllocationMin">Allocation Min</label>
                    <input type="text" class="form-control modal-input" id="portfolioAllocationMin">
                </div>
                <div id="portfolioAllocationMaxDiv" class="form-group">
                    <label for="portfolioAllocationMax">Allocation Max</label>
                    <input type="text" class="form-control modal-input" id="portfolioAllocationMax">
                </div>
                <div id="portfolioSortOrderDiv" class="form-group">
                    <label for="portfolioSortOrder">Sort Order</label>
                    <input type="text" class="form-control modal-input" id="portfolioSortOrder">
                </div>
            </div>
            <div class="modal-footer">
                <button id="addPortfolioSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Portfolio Modal -->
<div class="modal fade" id="editPortfolioModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="editPortfolioId" type="hidden">
                <div id="editPortfolioParentDiv" class="form-group">
                    <label for="editPortfolioNewParent">Top Portfolio</label>
                    <select id="editPortfolioNewParent" class="form-control modal-input selectpicker">
                        @foreach($parentPortfolios as $item)
                            <option id-parent="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="editPortfolioNameDiv" class="form-group">
                    <label for="editPortfolioName">Name</label>
                    <input type="text" class="form-control modal-input" id="editPortfolioName">
                </div>
                <div id="editPortfolioDescriptionDiv" class="form-group">
                    <label for="editPortfolioDescription">Description</label>
                    <textarea type="text" class="form-control modal-input" id="editPortfolioDescription"></textarea>
                </div>
                <div id="editPortfolioCurrencyDiv" class="form-group">
                    <label for="editPortfolioCurrency">Currency</label>
                    <select id="editPortfolioCurrency" class="form-control modal-input selectpicker">
                        <option selected>Choose currency...</option>
                        @foreach($currency as $item)
                            <option id-currency="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="editPortfolioAllocationMinDiv" class="form-group">
                    <label for="editPortfolioAllocationMin">Allocation Min</label>
                    <input type="text" class="form-control modal-input" id="editPortfolioAllocationMin">
                </div>
                <div id="editPortfolioAllocationMaxDiv" class="form-group">
                    <label for="editPortfolioAllocationMax">Allocation Max</label>
                    <input type="text" class="form-control modal-input" id="editPortfolioAllocationMax">
                </div>
                <div id="editPortfolioSortOrderDiv" class="form-group">
                    <label for="editPortfolioSortOrder">Sort Order</label>
                    <input type="text" class="form-control modal-input" id="editPortfolioSortOrder">
                </div>
                @can('crud users')
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="editPortfolioIsActive">
                        <label class="form-check-label" for="editPortfolioIsActive">Check if portfolio is active</label>
                    </div>
                @endcan
            </div>
            <div class="modal-footer">
{{--                <button id="deletePortfolioSubmit" type="button" class="btn btn-danger">Delete portfolio</button>--}}
                <button id="editPortfolioSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
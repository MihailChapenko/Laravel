<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="adminIdDiv" class="form-group">
                    <label for="adminId">Admin Id</label>
                    <select class="form-control" id="adminId">
                        <option value="" selected>Choose admin...</option>
                        @foreach($admins as $admin)
                            <option admin-id="{{$admin->id}}">{{ $admin->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="portfolioIdDiv" class="form-group">
                    <label for="portfolioId">Portfolio Id</label>
                    <select class="form-control" id="portfolioId">
                        <option value="" selected>Choose portfolio...</option>
                        @foreach($portfolio as $item)
                            <option portfolio-id="{{$item->id}}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="clientNameDiv" class="form-group">
                    <label for="clientName">Name</label>
                    <input type="text" class="form-control modal-input" id="clientName">
                </div>
                <div id="clientModelDiv" class="form-group">
                    <label for="clientModel">Model</label>
                    <input type="text" class="form-control modal-input" id="clientModel" maxlength="10">
                </div>
                <div id="clientThemeDiv" class="form-group">
                    <label for="clientTheme">Theme</label>
                    <input type="text" class="form-control modal-input" id="clientTheme" maxlength="30">
                </div>
                <div id="clientValueTableDiv" class="form-group">
                    <label for="clientValueTable">Value Table</label>
                    <input type="text" class="form-control modal-input" id="clientValueTable" maxlength="30">
                </div>
            </div>
            <div class="modal-footer">
                <button id="addClientSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="modalCenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="clientId" type="hidden">
                <input id="editClientAdminId" type="hidden">
                <div id="editClientNameDiv" class="form-group">
                    <label for="editClientName">Name</label>
                    <input type="text" class="form-control modal-input" id="editClientName">
                </div>
                <div id="editClientModelDiv" class="form-group">
                    <label for="editClientModel">Model</label>
                    <input type="text" class="form-control modal-input" id="editClientModel" maxlength="10">
                </div>
                <div id="editClientThemeDiv" class="form-group">
                    <label for="editClientTheme">Theme</label>
                    <input type="text" class="form-control modal-input" id="editClientTheme" maxlength="30">
                </div>
                <div id="editClientValueTableDiv" class="form-group">
                    <label for="editClientValueTable">Value Table</label>
                    <input type="text" class="form-control modal-input" id="editClientValueTable" maxlength="30">
                </div>
            </div>
            <div class="modal-footer">
{{--                <button id="deleteClientSubmit" type="button" class="btn btn-danger">Delete client</button>--}}
                <button id="editClientSubmit" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
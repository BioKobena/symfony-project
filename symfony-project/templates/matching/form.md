<div class="container mt-5">
				<div class="text-center mb-4">
					<h4 class="font-weight-bold">Matching</h4>
					<p class="text-muted">Trouvez les développeurs correspondant à vos critères</p>
				</div>

				<form id="matchingForm" class="bg-white m-4 p-4 shadow-sm rounded">
					<div
						class="row">
						<!-- Salaire -->
						<div class="col-md-4 mb-3">
							<label for="salary" class="form-label">Salaire minimum souhaité (€)</label>
							<input type="number" id="salary" name="salary" class="form-control" placeholder="Ex : 40000">
						</div>

						<!-- Localisation -->
						<div class="col-md-4 mb-3">
							<label for="location" class="form-label">Localisation</label>
							<input type="text" id="location" name="location" class="form-control" placeholder="Ex : Paris, France">
						</div>

						<!-- Niveau d'expérience -->
						<div class="col-md-4 mb-3">
							<label for="experience" class="form-label">Niveau d'expérience</label>
							<input type="number" id="experience" name="experience" class="form-control" min="1" max="5" placeholder="Ex : 3">
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="languages" class="form-label">Langages ou technologies</label>
						<select id="languages" name="languages[]" class="form-control" multiple>
							<option value="python">Python</option>
							<option value="javascript">JavaScript</option>
							<option value="java">Java</option>
							<option value="php">PHP</option>
							<option value="csharp">C#</option>
						</select>
						<small class="form-text text-muted">Maintenez Ctrl ou Cmd pour sélectionner plusieurs options</small>
					</div>

					<!-- Bouton de recherche -->
					<div class="text-center">
						<button type="button" id="matchButton" class="btn btn-primary">Trouver des Devs</button>
					</div>
				</form>
			</div>
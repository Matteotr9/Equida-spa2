import { h, render } from 'preact';
import { useState } from 'preact/hooks';

function AjouterChevalForm() {
  const [nom, setNom] = useState('');

  const handleChange = (event) => {
    setNom(event.target.value);
  };

  const handleSubmit = (event) => {
    event.preventDefault();
    // Soumettez le formulaire en utilisant une requête AJAX ou toute autre méthode de votre choix
    // ...

    // Réinitialisez le champ nom après la soumission réussie
    setNom('');
  };

  return (
    <form onSubmit={handleSubmit}>
      <h3>Ajouter un cheval</h3>
      <div>
        <label htmlFor="nom">Nom du cheval:</label>
        <input
          type="text"
          id="nom"
          value={nom}
          onChange={handleChange}
        />
      </div>
      <button type="submit">Ajouter</button>
    </form>
  );
}

// Utilisation du composant AjouterChevalForm

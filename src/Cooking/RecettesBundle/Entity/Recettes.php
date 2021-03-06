<?php

namespace Cooking\RecettesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Recettes
 *
 * @ORM\Table(name="recettes")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Recettes
{
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="recettes_images", fileNameProperty="imageName", size="imageSize")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=250)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=250, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="chapo", type="text", length=65535, nullable=true)
     */
    private $chapo;

    /**
     * @var string
     *
     * @ORM\Column(name="preparation", type="text", length=65535, nullable=true)
     */
    private $preparation;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredient", type="text", length=65535, nullable=true)
     */
    private $ingredient;

    /**
     * @var integer
     *
     * @ORM\Column(name="membre", type="integer", nullable=true)
     */
    private $membre;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=30, nullable=true)
     */
    private $couleur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCrea", type="datetime", nullable=true)
     */
    private $datecrea;

    /**
     * @var integer
     *
     * @ORM\Column(name="categorie", type="integer", nullable=true)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="tempsCuisson", type="string", length=50, nullable=true)
     */
    private $tempscuisson;

    /**
     * @var string
     *
     * @ORM\Column(name="tempsPreparation", type="string", length=50, nullable=true)
     */
    private $tempspreparation;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulte", type="string", length=50, nullable=true)
     */
    private $difficulte;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=50, nullable=true)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="idRecette", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrecette;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Recettes
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set chapo
     *
     * @param string $chapo
     *
     * @return Recettes
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }

    /**
     * Get chapo
     *
     * @return string
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set preparation
     *
     * @param string $preparation
     *
     * @return Recettes
     */
    public function setPreparation($preparation)
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * Get preparation
     *
     * @return string
     */
    public function getPreparation()
    {
        return $this->preparation;
    }

    /**
     * Set ingredient
     *
     * @param string $ingredient
     *
     * @return Recettes
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get ingredient
     *
     * @return string
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set membre
     *
     * @param integer $membre
     *
     * @return Recettes
     */
    public function setMembre($membre)
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get membre
     *
     * @return integer
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Recettes
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set datecrea
     *
     * @param \DateTime $datecrea
     *
     * @return Recettes
     */
    public function setDatecrea($datecrea)
    {
        $this->datecrea = $datecrea;

        return $this;
    }

    /**
     * Get datecrea
     *
     * @return \DateTime
     */
    public function getDatecrea()
    {
        return $this->datecrea;
    }

    /**
     * Set categorie
     *
     * @param integer $categorie
     *
     * @return Recettes
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return integer
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set tempscuisson
     *
     * @param string $tempscuisson
     *
     * @return Recettes
     */
    public function setTempscuisson($tempscuisson)
    {
        $this->tempscuisson = $tempscuisson;

        return $this;
    }

    /**
     * Get tempscuisson
     *
     * @return string
     */
    public function getTempscuisson()
    {
        return $this->tempscuisson;
    }

    /**
     * Set tempspreparation
     *
     * @param string $tempspreparation
     *
     * @return Recettes
     */
    public function setTempspreparation($tempspreparation)
    {
        $this->tempspreparation = $tempspreparation;

        return $this;
    }

    /**
     * Get tempspreparation
     *
     * @return string
     */
    public function getTempspreparation()
    {
        return $this->tempspreparation;
    }

    /**
     * Set difficulte
     *
     * @param string $difficulte
     *
     * @return Recettes
     */
    public function setDifficulte($difficulte)
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * Get difficulte
     *
     * @return string
     */
    public function getDifficulte()
    {
        return $this->difficulte;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Recettes
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Get idrecette
     *
     * @return integer
     */
    public function getIdRecette()
    {
        return $this->idrecette;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Recettes
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

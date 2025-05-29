<?php

namespace App\Cards;

/**
 * Card class is used to create a singular card object - which is a card from a french-suited, standard 52-card pack type of card.
 *
 * After creating the card object the user can set it's suit and value, determining what card it is.
 * The user can then access it's color, name, cards value, and the json-version of the cards value with the methods below.
 *
 */
class Cards
{
    protected ?string $cardValue;
    protected ?string $suit;
    protected ?string $color;
    protected ?string $name;
    /**
     * The constructor creates the base for the card object.
     * Card objects have a name, color, suit and a value.
     */
    public function __construct()
    {
        $this->cardValue = null;
        $this->suit = null;
        $this->color = null;
        $this->name = null;
    }
    /**
     * Method sets the cards values.
     *
     * Card objects suit and value determine what kind of card it is.
     * User set all the cards values by submitting the desired value and suit as a string.
     * The cards color is determined by the suit. The cards name is determined by the suit and value.
     */
    public function setCard(string $value, string $suit): void
    {
        $this->cardValue = $value;
        $this->suit = $suit;
        $this->setColor();
        $this->setName();
    }
    /**
     * Method sets the card objects color based on it's suit.
     * Valid suits are 'Hearts', 'Diamonds', 'Clubs' and 'Spades'
     */
    public function setColor(): void
    {
        switch ($this->suit) {
            case 'Hearts':
            case 'Diamonds':
                $this->color = "card red";
                break;
            case 'Clubs':
            case 'Spades':
                $this->color = "card black";
                break;
        }
    }
    /**
     * Method sets the card objects name based on it's value.
     *
     * A switch case handles the naming of the face card / court card or regular card.
     * For example: A card object with value '7' and suit 'Hearts' will be named '7 of Hearts'.
     * While a card object with the value '12' and suit 'Spades' will be named 'Queen of Spades'.
     */
    public function setName(): void
    {
        switch ($this->cardValue) {
            case 1:
                $this->name = "Ace of " . $this->suit;
                break;
            case 11:
                $this->name = "Jack of " . $this->suit;
                break;
            case 12:
                $this->name = "Queen of " . $this->suit;
                break;
            case 13:
                $this->name = "King of " . $this->suit;
                break;
            default:
                $this->name = $this->cardValue . " of " . $this->suit;
        }
    }
    /**
     * Method returns the card objects color.
     * It'll either be 'card red' or 'card black'.
     * Returns null if the card hasn't been set yet.
     */
    public function getColor(): ?string
    {
        return $this->color;
    }
    /**
     * Method returns the card objects name.
     * For example: 'Jack of Clubs'.
     * Returns null if the card hasn't been set yet.
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * Method returns the card objects value as a string.
     * For example: '4'.
     * Returns null if the card hasn't been set yet.
     */
    public function getCardValue(): ?string
    {
        return $this->cardValue;
    }
    /**
     * Method returns the cards value as a string in json-format.
     * for example: "[{'8'}]"
     */
    public function getAsString(): string
    {
        return "[{$this->cardValue}]";
    }
}

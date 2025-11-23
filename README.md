# Pokemon App

Laravel project for managing lists of pokemons. 

## Table of Contents

- [Installation](#installation)
- [API Endpoints](#api-endpoints)
- [Banned Pokemon](#banned-pokemon)
- [Custom Pokemon](#custom-pokemon)
- [License](#license)

---

## Installation
1. Clone repository:
```bash
cd pokemon-api
```
2. Install dependencies:
```bash
composer install
```
3. Configure API_KEY:
```bash
API_KEY='your_key'
```

## API Endpoints
### Banned Pokemon Api
#### Get Banned Pokemons List
```bash
GET /banned/list
header: X-Api-Key
```
#### Add Banned Pokemons
```bash
POST /banned/add
header: X-Api-Key
```
#### Delete Banned Pokemons 
```bash
DELETE /banned/delete/{name}
header: X-Api-Key
```
### Custom Pokemon Api

#### Get Custom Pokemons List
```bash
GET /info
```
#### Add Custom Pokemons
```bash
POST /add
header: X-Api-Key
```
#### Delete Custom Pokemons
```bash
DELETE /delete/{name}
header: X-Api-Key
```
#### Edit Custom Pokemons
```bash
PUT /edit/{name}
header: X-Api-Key
```
© 2025 WK. 

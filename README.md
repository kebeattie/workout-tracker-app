# Workout Tracker App

A Laravel-based web application for logging workouts, tracking progress, and visualizing exercise data.

## Features

- User authentication (login/register)
- Log workouts with multiple exercises
- Add custom exercises and save them as templates
- View, edit, and delete logged workouts
- Progress dashboard with charts (powered by Chart.js)
- User-specific exercise templates
- Responsive design with Blade components

## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL or compatible database
- Node.js & npm (for frontend assets, optional)

### Installation

1. **Clone the repository:**
   ```
   git clone https://github.com/yourusername/workout-tracker-app.git
   cd workout-tracker-app
   ```

2. **Install dependencies:**
   ```
   composer install
   ```

3. **Copy and configure `.env`:**
   ```
   cp .env.example .env
   ```
   - Set your database credentials in `.env`
   - Generate app key:
     ```
     php artisan key:generate
     ```

4. **Run migrations:**
   ```
   php artisan migrate
   ```

5. **(Optional) Compile frontend assets:**
   ```
   npm install
   npm run dev
   ```

6. **Start the development server:**
   ```
   php artisan serve
   ```
   Visit [http://localhost:8000](http://localhost:8000)

## Usage

- Register or log in.
- Log new workouts and add exercises (from templates or custom).
- View your workout history and delete entries if needed.
- Go to the Progress page to see charts of your exercise performance over time.

## Project Structure

- `app/Models` - Eloquent models (`Workout`, `Exercise`, `ExerciseTemplate`)
- `app/Http/Controllers` - Controllers for workouts, progress, profile, etc.
- `resources/views` - Blade templates for UI
- `routes/web.php` - Application routes

## API Endpoints

- `/api/progress-data?exercise=EXERCISE_NAME`  
  Returns JSON data for charting progress of a specific exercise.

## Contributing

Pull requests are welcome! For major changes, please open an issue first.

## License

MIT

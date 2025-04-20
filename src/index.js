// ... other imports
import { scheduleBreedingReadyCheck } from './services/breedingNotificationService';

// ... your existing code

// Initialize the breeding notification system
if (process.env.NODE_ENV === 'production') {
  scheduleBreedingReadyCheck();
}

// ... rest of your code
# VANYGRUB Laravel Application

## Deployment to Vercel

This Laravel application is configured for deployment on Vercel.

### Quick Deployment

1. **Install Vercel CLI**: `npm i -g vercel`
2. **Login to Vercel**: `vercel login`
3. **Deploy**: `vercel --prod`

### Environment Variables

Set these environment variables in your Vercel dashboard:

#### Required Variables
```
APP_NAME=VANYGRUB
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-vercel-app.vercel.app
```

#### Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password
```

#### Caching & Session (Pre-configured)
```
CACHE_DRIVER=array
SESSION_DRIVER=cookie
LOG_CHANNEL=stderr
QUEUE_CONNECTION=sync
```

### Database Setup

Choose a cloud database provider:
- **PlanetScale** (Recommended for MySQL)
- **Railway Database**
- **AWS RDS**
- **Google Cloud SQL**
- **Supabase PostgreSQL**

### File Storage Options

For file uploads, configure cloud storage:
- **AWS S3** (Most popular)
- **Cloudinary** (Image optimization)
- **Vercel Blob Storage** (Native integration)

### Configuration Files

- `vercel.json` - Vercel deployment configuration
- `api/index.php` - Serverless function entry point
- `.env.production` - Production environment template
- `.vercelignore` - Files to exclude from deployment

### Pre-Deployment Checklist

✅ **Environment Variables** - Set all required variables in Vercel dashboard  
✅ **Database** - Configure external database connection  
✅ **File Storage** - Setup cloud storage for uploads  
✅ **Domain** - Configure custom domain (optional)  
✅ **SSL** - Automatic with Vercel  

### Build Process

Vercel automatically:
1. Installs PHP dependencies via Composer
2. Builds frontend assets with Vite
3. Optimizes for serverless deployment
4. Serves static files via CDN

### Post-Deployment

1. **Run Migrations**: Use your database provider's console or migration tools
2. **Seed Data**: Import initial data if needed
3. **Test Functionality**: Verify all features work correctly
4. **Setup Monitoring**: Configure error tracking and monitoring

### Important Notes

- ⚡ **Serverless Functions**: Each request runs in an isolated environment
- 🗄️ **Stateless**: No file system persistence between requests
- 🔒 **Security**: Environment variables are encrypted
- 📊 **Scaling**: Automatic scaling based on traffic
- 💰 **Cost**: Pay per request execution

### Troubleshooting

**Common Issues:**
- File upload errors → Use cloud storage
- Session not persisting → Ensure cookie driver is used
- Database connection timeout → Use connection pooling
- Build failures → Check PHP/Node.js versions

**Debug Commands:**
```bash
vercel logs          # View function logs
vercel env ls        # List environment variables
vercel --debug       # Deploy with debug output
```
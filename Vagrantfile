Vagrant.configure("2") do |config|

	def Kernel.is_windows?
	    # Detect if we are running on Windows
	    processor, platform, *rest = RUBY_PLATFORM.split("-")
	    platform == 'mingw32'
	end

	config.vm.box = "ubuntu/trusty64"
    config.vm.box_check_update = false
    config.vm.hostname = "local.dev"
    config.vm.network "forwarded_port", guest: 22, host: 10022
    config.vm.network "forwarded_port", guest: 80, host: 10080
    config.vm.network "private_network", ip: "10.0.1.2"

	config.vm.provider :virtualbox do |vb|
		vb.customize ["modifyvm", :id, "--memory", 512]
        vb.memory = 512
        vb.cpus = 1
        vb.customize ["modifyvm", :id, "--cpuexecutioncap", "25"]
	end

    config.vm.provision :puppet do |puppet|
        puppet.manifests_path = "local.dev/manifests"
        puppet.manifest_file = "default.pp"
    end

	if Kernel.is_windows?
		config.vm.synced_folder "./app", "/media/app" #, type: "smb"
		config.vm.synced_folder "./local.dev/database", "/media/database" #, type: "smb"
	else
		config.vm.synced_folder "./app", "/media/app", type: "nfs"
		config.vm.synced_folder "./local.dev/database", "/media/database", type: "nfs"
	end

end
